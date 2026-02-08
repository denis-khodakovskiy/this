<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App\This\Core\Request\Meta;

use This\Contracts\RequestMetaCollectorInterface;

final class RequestMetaCollector implements RequestMetaCollectorInterface
{
    /** @var array<non-empty-string, mixed> */
    private array $meta = [];

    public function set(string $key, mixed $value): void
    {
        $this->meta[$key] = $value;
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->meta[$key] ?? $default;
    }

    public function has(string $key): bool
    {
        return isset($this->meta[$key]);
    }

    public function all(): array
    {
        return $this->meta;
    }

    public function clear(): void
    {
        $this->meta = [];
    }
}
