<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\Migrations\Schema;

use This\ORM\Migrations\Command\AlterTableCommand;
use This\ORM\Migrations\Command\CreateTableCommand;

final class SchemaBuilder implements SchemaBuilderInterface
{
    public function __construct(
        private readonly SchemaCommandCollector $commandCollector,
    ) {
    }

    private bool $rollbackable = true;

    /**
     * @throws \Exception
     */
    public function createTable(string $name, callable $definition): void
    {
        if ($this->rollbackable) {
            throw new \Exception('You have to explicitly call nonRollBack() method to create a table.');
        }

        $collector = new TableCommandCollector();
        $tableBuilder = new TableDefinition($name, $collector);
        $definition($tableBuilder);

        $this->commandCollector->add(new CreateTableCommand($tableBuilder));
    }

    /**
     * @throws \Exception
     */
    public function dropTable(string $name): void
    {
        if ($this->rollbackable) {
            throw new \Exception('You have to explicitly call nonRollBack() method to drop a table.');
        }
    }

    /**
     * @throws \Exception
     */
    public function table(string $name, callable $definition): void
    {
        if ($this->rollbackable) {
            throw new \Exception('You have to explicitly call nonRollBack() method to alter a table.');
        }

        $collector = new TableCommandCollector();
        $tableBuilder = new TableDefinition($name, $collector);
        $definition($tableBuilder);

        $this->commandCollector->add(new AlterTableCommand($tableBuilder));
    }

    public function nonRollBack(): SchemaBuilderInterface
    {
        $clone = clone $this;
        $clone->rollbackable = false;

        return $clone;
    }
}
