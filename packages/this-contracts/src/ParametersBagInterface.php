<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Contracts;

interface ParametersBagInterface
{
    public function set(string|int $key, mixed $value): void;

    public function get(string|int $key, $default = null): mixed;

    public function has(string|int $key): bool;
}
