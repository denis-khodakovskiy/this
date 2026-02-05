<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\Migrations\Command;

use This\ORM\Migrations\Schema\MigrationCommandInterface;

final readonly class AlterColumnCommand implements MigrationCommandInterface
{
    public function __construct(
        private string $tableName,
        private ColumnDefinition $columnDefinition,
    ) {
    }

    public function getColumnDefinition(): ColumnDefinition
    {
        return $this->columnDefinition;
    }

    public function getTableName(): string
    {
        return $this->tableName;
    }

    public function getDescription(): ?string
    {
        return sprintf(
            'Altering column <b>%s</b>',
            $this->getColumnDefinition()->getName(),
        );
    }
}
