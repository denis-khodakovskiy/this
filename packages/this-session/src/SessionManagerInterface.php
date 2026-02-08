<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Session;

interface SessionManagerInterface
{
    public function set(string $key, $value): void;

    public function get(string $key, $default = null);

    public function has(string $key): bool;

    public function remove(string $key): void;

    public function invalidate(): void;

    public function flush(): void;
}
