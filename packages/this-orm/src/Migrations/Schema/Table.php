<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\Migrations\Schema;

use This\ORM\Migrations\Operations\ColumnBuilder;

final readonly class Table
{
    public function __construct(
        private string $name,
        private TableCommandCollector $collector
    ) {
    }

    public function addColumn(string $name): ColumnBuilder
    {
        $builder = new ColumnBuilder($name);
        $this->collector->addCommand($builder);

        return $builder;
    }

    public function dropColumn(string $name): void
    {

    }

    public function renameColumn(string $name): void
    {

    }

    public function primary($column)
    {

    }

    public function index(...$columns)
    {

    }

    public function unique(...$columns)
    {

    }

    public function dropIndex($name)
    {

    }

    public function getName(): string
    {
        return $this->name;
    }
}
