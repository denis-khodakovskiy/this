<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\Query;

final class Delete extends AbstractQuery
{
    private bool $all = false;

    private function __construct(string $schemaClass, ?string $alias = null)
    {
        $this->schemaClass = $schemaClass;
        $this->alias = $alias;
    }

    public static function from(string $schemaClass, ?string $alias = null): self
    {
        return new self($schemaClass, $alias);
    }

    public function all(): self
    {
        $this->all = true;

        return $this;
    }

    public function getAll(): bool
    {
        return $this->all;
    }
}
