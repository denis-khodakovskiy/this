<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\Migrations\Compiler;

use This\ORM\Migrations\Command\AddColumnCommand;
use This\ORM\Migrations\Command\AddUniqueIndexCommand;
use This\ORM\Migrations\Command\AlterTableCommand;
use This\ORM\Migrations\Command\AlterColumnCommand;
use This\ORM\Migrations\Command\CreateForeignKeyCommand;
use This\ORM\Migrations\Command\CreateIndexCommand;
use This\ORM\Migrations\Command\AddPrimaryKeyCommand;
use This\ORM\Migrations\Command\CreateTableCommand;
use This\ORM\Migrations\Command\DropColumnCommand;
use This\ORM\Migrations\Command\DropForeignKeyCommand;
use This\ORM\Migrations\Command\DropIndexCommand;
use This\ORM\Migrations\Command\DropPrimaryKeyCommand;
use This\ORM\Migrations\Command\DropTableCommand;
use This\ORM\Migrations\Schema\MigrationCommandInterface;

final class Compiler
{
    public function compile(MigrationCommandInterface $command): string
    {
        return match (true) {
            $command instanceof AlterTableCommand => $this->alterTable($command),
            $command instanceof CreateTableCommand => $this->createTable($command),
            //$command instanceof DropTableCommand => $this->dropTable($command),

            $command instanceof AddColumnCommand => $this->addColumn($command),
            $command instanceof AddPrimaryKeyCommand => $this->createPrimaryKey($command),
            $command instanceof AddUniqueIndexCommand => $this->addUniqueIndex($command),
            $command instanceof AlterColumnCommand => $this->changeColumnType($command),
            $command instanceof CreateForeignKeyCommand => $this->createForeignKey($command),
            $command instanceof CreateIndexCommand => $this->createIndex($command),
            $command instanceof DropColumnCommand => $this->dropColumn($command),
            $command instanceof DropForeignKeyCommand => $this->dropForeignKey($command),
            $command instanceof DropIndexCommand => $this->dropIndex($command),
            $command instanceof DropPrimaryKeyCommand => $this->dropPrimaryKey($command),
        };
    }

    private function createTable(CreateTableCommand $command): string
    {
        $sql = sprintf(
            "CREATE TABLE `%s` (\n%s\n)",
            $command->tableDefinition->getTableName(),
            implode(",\n", array_map(
                fn (MigrationCommandInterface $expression) => $this->compile($command),
                array_filter(
                    $command->tableDefinition->getTableCommandCollector()->getCommands(),
                    fn (MigrationCommandInterface $expression) => $expression instanceof AddColumnCommand,
                ),
            )),
        );

        if ($command->tableDefinition->getEngine()) {
             $sql .= sprintf(" ENGINE = %s", $command->tableDefinition->getEngine());
        }

        if ($command->tableDefinition->getCharset()) {
            $sql .= sprintf(" DEFAULT CHARSET = %s", $command->tableDefinition->getCharset());
        }

        if ($command->tableDefinition->getCollation()) {
            $sql .= sprintf(" COLLATE = %s", $command->tableDefinition->getCollation());
        }

        if ($command->tableDefinition->getComment()) {
            $sql .= sprintf(" COMMENT = '%s'", $command->tableDefinition->getComment());
        }

        return $sql;
    }

    public function alterTable(AlterTableCommand $command): string
    {
        return implode("\n", array_filter([
            "ALTER TABLE `{$command->tableDefinition->getTableName()}`",
            $command->tableDefinition->getNewName() ? "RENAME TO `{$command->tableDefinition->getNewName()}`" : null,
            $command->tableDefinition->getComment() ? "COMMENT = '{$command->tableDefinition->getComment()}'": null,
            $command->tableDefinition->getCharset() ? "DEFAULT CHARSET = {$command->tableDefinition->getCharset()}": null,
            $command->tableDefinition->getCollation() ? "COLLATE = {$command->tableDefinition->getCollation()}": null,
            $command->tableDefinition->getEngine() ? "ENGINE = {$command->tableDefinition->getEngine()}": null,
        ]));
    }

    private function addColumn(AddColumnCommand $command): string
    {
        $type = $this->mapType($command->getColumnDefinition()->getType());

        $parts = [
            "`{$command->getColumnDefinition()->getName()}`",
            mb_strtoupper(
                $command->getColumnDefinition()->getLength()
                    ? "{$type}({$command->getColumnDefinition()->getLength()})"
                    : $type,
            ),
        ];

        if ($command->getColumnDefinition()->getCollation()) {
            $parts[] = "COLLATE {$command->getColumnDefinition()->getCollation()}";
        }

        if (!$command->getColumnDefinition()->isNullable()) {
            $parts[] = 'NOT NULL';
        }

        if ($command->getColumnDefinition()->isAutoIncrement()) {
            $parts[] = 'AUTO_INCREMENT';
        }

        if ($command->getColumnDefinition()->getDefaultValue()) {
            $parts[] = 'DEFAULT ' . is_string($command->getColumnDefinition()->getDefaultValue())
                ? "'{$command->getColumnDefinition()->getDefaultValue()}'"
                : $command->getColumnDefinition()->getDefaultValue();
        }

        if ($command->getColumnDefinition()->getDefaultExpression()) {
            $parts[] = "DEFAULT {$command->getColumnDefinition()->getDefaultExpression()}";
        }

        if ($command->getColumnDefinition()->getComment()) {
            $parts[] = "COMMENT '{$command->getColumnDefinition()->getComment()}'";
        }

        return implode(' ', $parts);
    }

    private function createIndex(CreateIndexCommand $expression): string
    {
        return implode(' ', [
            $expression->isUnique() ? 'CREATE UNIQUE INDEX' : 'CREATE INDEX',
            $expression->getName()
                ?? sprintf(
                    '%s_%s_idx',
                    $expression->getTable(),
                    implode('_', $expression->getColumns()
                )
            ),
            'ON',
            $expression->getTable(),
            sprintf('(%s)', implode(', ', array_map(
                fn (string $column) => $column,
                $expression->getColumns(),
            ))),
        ]);
    }

    private function createPrimaryKey(AddPrimaryKeyCommand $expression): string
    {
        return "ALTER TABLE `{$expression->table}` ADD PRIMARY KEY (" . implode(', ', $expression->getColumns()) . ")";
    }

    private function createForeignKey(CreateForeignKeyCommand $expression): string
    {
        $sql = "ALTER TABLE `{$expression->getTable()}` ADD CONSTRAINT FOREIGN KEY (`{$expression->getColumn()}`)";
        $sql .= " REFERENCES `{$expression->getReferenceTable()}` (`{$expression->getReferenceColumn()}`)";
        $sql .= " ON DELETE {$expression->getOnDelete()} ON UPDATE {$expression->getOnUpdate()}";

        return $sql;
    }

    public function addUniqueIndex(AddUniqueIndexCommand $command): string
    {
        $columnsList = implode(', ', array_map(
            fn (string $columnName) => "`{$columnName}`",
            $command->getColumns(),
        ));

        return "CREATE UNIQUE INDEX `{$command->getName()}` ON `{$command->getTableName()}` ({$columnsList})";
    }

    public function changeColumnType(AlterColumnCommand $command): string
    {
        $type = $this->mapType($command->getColumnDefinition()->getType());

        $parts = [
            'ALTER TABLE',
            "`{$command->getTableName()}`",
            'CHANGE COLUMN',
            "`{$command->getColumnDefinition()->getName()}`",
            "`{$command->getColumnDefinition()->getNewName()}`",
            mb_strtoupper(
                $command->getColumnDefinition()->getLength()
                    ? "{$type}({$command->getColumnDefinition()->getLength()})"
                    : $type,
            ),
        ];

        if ($command->getColumnDefinition()->getCollation()) {
            $parts[] = "COLLATE {$command->getColumnDefinition()->getCollation()}";
        }

        if (!$command->getColumnDefinition()->isNullable()) {
            $parts[] = 'NOT NULL';
        }

        if ($command->getColumnDefinition()->isAutoIncrement()) {
            $parts[] = 'AUTO_INCREMENT';
        }

        if ($command->getColumnDefinition()->getDefaultValue()) {
            $parts[] = 'DEFAULT ' . is_string($command->getColumnDefinition()->getDefaultValue())
                ? "'{$command->getColumnDefinition()->getDefaultValue()}'"
                : $command->getColumnDefinition()->getDefaultValue();
        }

        if ($command->getColumnDefinition()->getDefaultExpression()) {
            $parts[] = "DEFAULT {$command->getColumnDefinition()->getDefaultExpression()}";
        }

        if ($command->getColumnDefinition()->getComment()) {
            $parts[] = "COMMENT '{$command->getColumnDefinition()->getComment()}'";
        }

        return implode(' ', array_filter($parts));
    }

    public function dropColumn(DropColumnCommand $command): string
    {
        return "ALTER TABLE `{$command->getTableName()}` DROP COLUMN `{$command->getColumn()}`";
    }

    public function dropForeignKey(DropForeignKeyCommand $command): string
    {
        return "ALTER TABLE `{$command->getTableName()}` DROP FOREIGN KEY `{$command->getForeignKeyName()}`";
    }

    public function dropIndex(DropIndexCommand $command): string
    {
        return "DROP INDEX `{$command->getIndexName()}` ON `{$command->getTableName()}`";
    }

    public function dropPrimaryKey(DropPrimaryKeyCommand $command): string
    {
        return "ALTER TABLE `{$command->tableName}` DROP PRIMARY KEY";
    }

    private function mapType(string $type): string
    {
        return match ($type) {
            'string' => 'VARCHAR',
            'integer' => 'INT',
            'boolean' => 'TINYINT',
            default => mb_strtoupper($type),
        };
    }
}
