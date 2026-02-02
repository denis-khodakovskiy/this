<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Contracts;

interface EnvContainerInterface
{
    public function get(string $key, mixed $default = null): mixed;

    public function has(string $key): bool;
}
