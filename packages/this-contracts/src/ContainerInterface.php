<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Contracts;

interface ContainerInterface extends \Psr\Container\ContainerInterface
{
    public function bind(string $id, callable $definition, int $priority = 1000): self;

    public function singleton(string $id, callable $definition, int $priority = 1000): self;

    public function has(string $id): bool;

    public function get(string $id): mixed;

    public function freeze(): void;

    public function isFreeze(): bool;
}
