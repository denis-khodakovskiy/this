<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\Migrations\Command;

use This\ORM\Migrations\Schema\MigrationCommandInterface;
use This\ORM\Migrations\Schema\TableBuilder;

final readonly class CreateTableCommand implements MigrationCommandInterface
{
    public function __construct(
        public TableBuilder $tableBuilder,
    ) {
    }

    public function getDescription(): ?string
    {
        return "Creating {$this->tableBuilder->getName()} table";
    }
}
