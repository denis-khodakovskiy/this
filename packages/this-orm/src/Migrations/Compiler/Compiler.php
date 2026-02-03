<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\Migrations\Compiler;

use This\ORM\Migrations\Command\AddColumnCommand;
use This\ORM\Migrations\Command\CreateForeignKeyCommand;
use This\ORM\Migrations\Command\CreateIndexCommand;
use This\ORM\Migrations\Command\CreatePrimaryKeyCommand;
use This\ORM\Migrations\Command\CreateTableCommand;
use This\ORM\Migrations\Schema\MigrationCommandInterface;

final class Compiler
{
    public function compile(MigrationCommandInterface $expression): string
    {
        return match (true) {
            $expression instanceof CreateTableCommand => $this->createTable($expression),
            $expression instanceof AddColumnCommand => $this->addColumn($expression),
            $expression instanceof CreateIndexCommand => $this->createIndex($expression),
            $expression instanceof CreatePrimaryKeyCommand => $this->createPrimaryKey($expression),
            $expression instanceof CreateForeignKeyCommand => $this->createForeignKey($expression),
        };
    }

    private function createTable(CreateTableCommand $expression): string
    {
        return sprintf(
            "CREATE TABLE `%s` (\n%s\n)",
            $expression->tableBuilder->getName(),
            implode(",\n", array_map(
                fn (MigrationCommandInterface $expression) => $this->compile($expression),
                $expression->tableBuilder->getCollector()->getCommands(),
            )),
        );
    }

    private function addColumn(AddColumnCommand $expression): string
    {
        $type = $this->mapType($expression->getType());

        $parts = [
            "`{$expression->getName()}`",
            mb_strtoupper(
                $expression->getLength()
                    ? "{$type}({$expression->getLength()})"
                    : $type,
            ),
        ];

        if ($expression->getCollation()) {
            $parts[] = "COLLATE {$expression->getCollation()}";
        }

        if (!$expression->isNullable()) {
            $parts[] = 'NOT NULL';
        }

        if ($expression->isAutoIncrement()) {
            $parts[] = 'AUTO_INCREMENT';
        }

        if ($expression->isPrimary()) {
            $parts[] = 'PRIMARY KEY';
        }

        if ($expression->getDefaultValue()) {
            $parts[] = 'DEFAULT ' . is_string($expression->getDefaultValue())
                ? "'{$expression->getDefaultValue()}'"
                : $expression->getDefaultValue();
        }

        if ($expression->getDefaultExpression()) {
            $parts[] = "DEFAULT {$expression->getDefaultExpression()}";
        }

        if ($expression->getComment()) {
            $parts[] = "COMMENT '{$expression->getComment()}'";
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

    private function createPrimaryKey(CreatePrimaryKeyCommand $expression): string
    {
        return "ALTER TABLE `{$expression->table}` ADD PRIMARY KEY (" . implode(', ', $expression->columns) . ")";
    }

    private function createForeignKey(CreateForeignKeyCommand $expression): string
    {
        $sql = "ALTER TABLE `{$expression->getTable()}` ADD CONSTRAINT FOREIGN KEY (`{$expression->getColumn()}`)";
        $sql .= " REFERENCES `{$expression->getReferenceTable()}` (`{$expression->getReferenceColumn()}`)";
        $sql .= " ON DELETE {$expression->getOnDelete()} ON UPDATE {$expression->getOnUpdate()};";

        return $sql;
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
