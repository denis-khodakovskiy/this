<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\Migrations\Command;

use This\ORM\Migrations\Schema\MigrationCommandInterface;
use This\ORM\Migrations\Schema\TableDefinition;

final readonly class CreateTableCommand implements MigrationCommandInterface
{
    public function __construct(
        public TableDefinition $tableDefinition,
    ) {
    }

    public function getDescription(): ?string
    {
        return "Creating <b>{$this->tableDefinition->getTableName()}</b> table";
    }
}
