<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\Migrations\Schema;

use This\ORM\Migrations\Command\AddColumnCommand;
use This\ORM\Migrations\Command\ChangeColumnTypeCommand;
use This\ORM\Migrations\Command\DropColumnCommand;
use This\ORM\Migrations\Command\RenameColumnCommand;

final readonly class TableBuilder
{
    public function __construct(
        private string $name,
        private TableCommandCollector $collector
    ) {
    }

    public function addColumn(string $name): AddColumnCommand
    {
        $expression = new AddColumnCommand($name);
        $this->collector->addCommand($expression);

        return $expression;
    }

    public function dropColumn(string $name): void
    {
        $this->collector->addCommand(new DropColumnCommand($name));
    }

    public function renameColumn(string $name): RenameColumnCommand
    {
        $expression = new RenameColumnCommand($name);
        $this->collector->addCommand($expression);

        return $expression;
    }

    public function changeColumnType(string $name): ChangeColumnTypeCommand
    {
        $expression = new ChangeColumnTypeCommand($name);
        $this->collector->addCommand($expression);

        return $expression;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getCollector(): TableCommandCollector
    {
        return $this->collector;
    }

    public function timestamps(): void
    {
        $this->addColumn('createdAt')->dateTime()->defaultExpression('NOW()');
        $this->addColumn('updatedAt')->dateTime()->defaultExpression('NOW()');
        $this->addColumn('deletedAt')->dateTime()->nullable();
    }
}
