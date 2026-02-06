<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App\This\Infrastructure\Container;

use This\Contracts\ContainerInterface;

final class Container implements ContainerInterface
{
    /** @var array<non-empty-string, mixed> */
    private array $bindings = [];

    /** @var array<non-empty-string, bool> */
    private array $singletons = [];

    /** @var array<non-empty-string, mixed> */
    private array $instances = [];

    /** @var array<non-empty-string, bool> */
    private array $resolving = [];

    /** @var array<non-empty-string, int> */
    private array $priorities = [];

    private bool $freeze = false;

    public function bind(string $id, callable $definition, int $priority = 1000): self
    {
        $this->checkFreeze(id: $id);

        if ($this->has(id: $id) && $priority > $this->priorities[$id]) {
            $this->bindings[$id] = $definition;
            $this->priorities[$id] = $priority;

            return $this;
        }

        $this->bindings[$id] = $definition;
        $this->priorities[$id] = $priority;

        return $this;
    }

    public function singleton(string $id, callable $definition, int $priority = 1000): self
    {
        $this->checkFreeze(id: $id);

        if ($this->has(id: $id) && $priority > $this->priorities[$id]) {
            $this->bindings[$id] = $definition;
            $this->singletons[$id] = true;
            $this->priorities[$id] = $priority;

            return $this;
        }

        $this->bindings[$id] = $definition;
        $this->singletons[$id] = true;
        $this->priorities[$id] = $priority;

        return $this;
    }

    public function has(string $id): bool
    {
        return isset($this->bindings[$id]);
    }

    public function get(string $id): mixed
    {
        if (!$this->has(id: $id)) {
            throw new \RuntimeException(message: "No such service {$id}");
        }

        if (isset($this->resolving[$id])) {
            throw new \RuntimeException(message: "Cycle link detected for service {$id}");
        }

        if (isset($this->singletons[$id])) {
            if (!isset($this->instances[$id])) {
                $this->instances[$id] = $this->getInstance(id: $id);
            }

            return $this->instances[$id];
        }

        return $this->getInstance(id: $id);
    }

    public function freeze(): void
    {
        $this->freeze = true;
    }

    public function isFreeze(): bool
    {
        return $this->freeze;
    }

    private function getInstance(string $id): mixed
    {
        try {
            $this->resolving[$id] = true;
            $instance = $this->bindings[$id]($this);
        } finally {
            unset($this->resolving[$id]);
        }

        return $instance;
    }

    private function checkFreeze(?string $id = null): void
    {
        if ($this->freeze) {
            throw new \RuntimeException(
                message: $id
                    ? "Service {$id} can not be bind due to container freeze"
                    : 'Binding while container freeze',
            );
        }
    }
}
