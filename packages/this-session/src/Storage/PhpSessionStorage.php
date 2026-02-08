<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Session\Storage;

final class PhpSessionStorage implements SessionStorageInterface
{
    public function read(string $sessionId): array
    {
        return $_SESSION[$sessionId] ?? [];
    }

    public function write(string $sessionId, array $data): void
    {
        $_SESSION[$sessionId] = $data;
    }

    public function delete(string $sessionId): void
    {
        unset($_SESSION[$sessionId]);
    }
}