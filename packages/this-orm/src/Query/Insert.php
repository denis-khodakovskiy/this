<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\Query;

final class Insert extends AbstractQuery
{
    /** @var array<non-empty-string, mixed> */
    private array $rows = [];

    private function __construct(string $schemaClass)
    {
        $this->schemaClass = $schemaClass;
    }

    public static function into(string $schemaClass): self
    {
        return new self($schemaClass);
    }

    public function values(array $values): self
    {
        $this->rows = $values;

        return $this;
    }

    public function getRows(): array
    {
        return $this->rows;
    }
}
