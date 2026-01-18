<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App\This\Infrastructure\Config;

final readonly class PathResolver
{
    public function __construct(
        private array $aliases
    ) {
    }

    public function resolve(string $path): string
    {
        return str_replace(
            array_keys($this->aliases),
            array_values($this->aliases),
            $path,
        );
    }
}
