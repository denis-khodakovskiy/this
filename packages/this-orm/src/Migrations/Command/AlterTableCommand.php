<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\Migrations\Command;

use This\ORM\Migrations\Schema\MigrationCommandInterface;
use This\ORM\Migrations\Schema\TableBuilder;

final readonly class AlterTableCommand implements MigrationCommandInterface
{
    public function __construct(
        public TableBuilder $tableDefinition,
    ) {
    }

    public function getDescription(): ?string
    {
        return "Altering table <b>{$this->tableDefinition->getTableName()}</b> table";
    }
}
