<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\Migrations\Schema;

use This\ORM\Migrations\Command\AddColumnCommand;
use This\ORM\Migrations\Command\AddPrimaryKeyCommand;
use This\ORM\Migrations\Command\AddUniqueIndexCommand;
use This\ORM\Migrations\Command\AlterColumnCommand;
use This\ORM\Migrations\Command\ColumnDefinition;
use This\ORM\Migrations\Command\CreateForeignKeyCommand;
use This\ORM\Migrations\Command\CreateIndexCommand;
use This\ORM\Migrations\Command\DropColumnCommand;
use This\ORM\Migrations\Command\DropForeignKeyCommand;
use This\ORM\Migrations\Command\DropIndexCommand;
use This\ORM\Migrations\Command\DropPrimaryKeyCommand;

final class TableBuilder
{
    private string $collation = 'utf8mb4_general_ci';

    private ?string $engine = null;

    private string $charset = 'utf8mb4';

    private ?string $comment = null;

    private ?string $newName = null;

    public function __construct(
        private readonly string $tableName,
        private readonly TableCommandCollector $tableCommandCollector,
        private readonly SchemaCommandCollector $schemaCommandCollector,
    ) {
    }

    public function addColumn(string $columnName): ColumnDefinition
    {
        $columnDefinition = new ColumnDefinition($columnName);
        $command = new AddColumnCommand($this->tableName, $columnDefinition);
        $this->tableCommandCollector->addCommand($command);

        return $columnDefinition;
    }

    public function addPrimaryKey(string ...$columns): void
    {
        $this->schemaCommandCollector->addCommand(new AddPrimaryKeyCommand($this->tableName, $columns));
    }

    public function addUniqueIndex(string ...$columns): AddUniqueIndexCommand
    {
        $command = new AddUniqueIndexCommand($this->tableName, $columns);
        $this->schemaCommandCollector->addCommand($command);

        return $command;
    }

    public function changeColumnType(string $columnName): ColumnDefinition
    {
        $columnDefinition = $this->addColumn($columnName);
        $command = new AlterColumnCommand($this->tableName, $columnDefinition);
        $this->schemaCommandCollector->addCommand($command);

        return $columnDefinition;
    }

    public function createForeignKey(
        string $column,
        string $foreignTable,
        string $foreignColumn,
    ): CreateForeignKeyCommand {
        $command = new CreateForeignKeyCommand($this->tableName, $column, $foreignTable, $foreignColumn);
        $this->schemaCommandCollector->addCommand($command);

        return $command;
    }

    public function addIndex(string ...$columns): CreateIndexCommand
    {
        $command = new CreateIndexCommand($this->tableName, $columns);
        $this->schemaCommandCollector->addCommand($command);

        return $command;
    }

    public function dropColumn(string $columnName): void
    {
        $this->schemaCommandCollector->addCommand(new DropColumnCommand($this->tableName, $columnName));
    }

    public function dropForeignKey(string $foreignKeyName): void
    {
        $this->schemaCommandCollector->addCommand(new DropForeignKeyCommand($this->tableName, $foreignKeyName));
    }

    public function dropIndex(string $indexName): void
    {
        $this->schemaCommandCollector->addCommand(new DropIndexCommand($this->tableName, $indexName));
    }

    public function dropPrimaryKey(): void
    {
        $this->schemaCommandCollector->addCommand(new DropPrimaryKeyCommand($this->tableName));
    }

    public function getTableName(): string
    {
        return $this->tableName;
    }

    public function getTableCommandCollector(): TableCommandCollector
    {
        return $this->tableCommandCollector;
    }

    public function timestamps(): void
    {
        $this->addColumn('createdAt')->dateTime()->defaultExpression('NOW()');
        $this->addColumn('updatedAt')->dateTime()->defaultExpression('NOW()');
        $this->addColumn('deletedAt')->dateTime()->nullable();
    }

    public function collation(string $collation): self
    {
        $this->collation = $collation;

        return $this;
    }

    public function getCollation(): ?string
    {
        return $this->collation;
    }

    public function engine(string $engine): self
    {
        $this->engine = $engine;

        return $this;
    }

    public function getEngine(): ?string
    {
        return $this->engine;
    }

    public function charset(string $charset): self
    {
        $this->charset = $charset;

        return $this;
    }

    public function getCharset(): string
    {
        return $this->charset;
    }

    public function comment(string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function renameTo(string $newName): self
    {
        $this->newName = $newName;

        return $this;
    }

    public function getNewName(): ?string
    {
        return $this->newName;
    }
}
