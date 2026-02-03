<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\Migrations\Schema;

use This\ORM\Migrations\Command\CreateForeignKeyCommand;
use This\ORM\Migrations\Command\CreateIndexCommand;
use This\ORM\Migrations\Command\CreateTableCommand;

final class SchemaBuilder implements SchemaBuilderInterface
{
    public function __construct(
        private readonly SchemaCommandCollector $commandCollector,
    ) {
    }

    private bool $rollbackable = true;

    public function createTable(string $name, callable $definition): void
    {
        if ($this->rollbackable) {
            throw new \Exception('You have to explicitly call nonRollBack() method to create a table.');
        }

        $collector = new TableCommandCollector();
        $table = new TableBuilder($name, $collector);
        $definition($table);

        $this->commandCollector->add(new CreateTableCommand($table));
    }

    public function createIndex(string $table, array $columns): CreateIndexCommand
    {
        $command = new CreateIndexCommand($table, $columns);
        $this->commandCollector->add($command);

        return $command;
    }

    public function createForeignKey(
        string $table,
        string $column,
        string $referenceTable,
        string $referenceColumn
    ): CreateForeignKeyCommand {
        $command = new CreateForeignKeyCommand($table, $column, $referenceTable, $referenceColumn);
        $this->commandCollector->add($command);

        return $command;
    }

    public function dropTable(string $name): void
    {

    }

    public function table(string $name, callable $definition): void
    {

    }

    public function nonRollBack(): SchemaBuilderInterface
    {
        $clone = clone $this;
        $clone->rollbackable = false;

        return $clone;
    }
}
