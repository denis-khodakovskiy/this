<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Session;

use This\Session\ID\Resolver\SessionIdResolverInterface;
use This\Session\Storage\SessionStorageInterface;

final class SessionManager implements SessionManagerInterface
{
    private bool $dirty = false;

    private array $data = [];

    private ?string $sessionId = null;

    public function __construct(
        private readonly SessionStorageInterface $sessionStorage,
        private readonly SessionIdResolverInterface $sessionIdResolver,
        private readonly bool $autoSave = true,
    ) {
        $this->sessionId = $this->sessionIdResolver->resolve();
        $this->data = $this->sessionStorage->read($this->sessionId);
    }

    public function set(string $key, $value): void
    {
        $this->data[$key] = $value;

        $this->dirty = true;

        if ($this->autoSave) {
            $this->flush();
        }
    }

    public function get(string $key, $default = null)
    {
        return $this->data[$key] ?? $default;
    }

    public function has(string $key): bool
    {
        return isset($this->data[$key]);
    }

    public function remove(string $key): void
    {
        unset($this->data[$key]);

        $this->dirty = true;

        if ($this->autoSave) {
            $this->flush();
        }
    }

    public function invalidate(): void
    {
        $this->data = [];

        $this->dirty = true;

        if ($this->autoSave) {
            $this->flush();
        }
    }

    public function flush(): void
    {
        if ($this->dirty) {
            $this->sessionStorage->write($this->sessionId, $this->data);
        }

        $this->dirty = false;
    }
}
