<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\Migrations\Command;

use This\ORM\Migrations\Schema\MigrationCommandInterface;

final readonly class DropForeignKeyCommand implements MigrationCommandInterface
{
    public function __construct(
        private string $tableName,
        private string $foreignKeyName,
    ) {
    }

    public function getTableName(): string
    {
        return $this->tableName;
    }

    public function getForeignKeyName(): string
    {
        return $this->foreignKeyName;
    }

    public function getDescription(): ?string
    {
        return "Dropping foreign key <b>$this->foreignKeyName</b>";
    }
}
