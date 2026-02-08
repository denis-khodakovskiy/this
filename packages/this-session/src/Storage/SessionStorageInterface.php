<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Session\Storage;

interface SessionStorageInterface
{
    public function read(string $sessionId): array;

    public function write(string $sessionId, array $data): void;

    public function delete(string $sessionId): void;
}
