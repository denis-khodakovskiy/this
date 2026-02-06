<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\Migrations\Command;

use This\ORM\Migrations\Schema\MigrationCommandInterface;

final readonly class DropTableCommand implements MigrationCommandInterface
{
    public function __construct(
        public string $tableName,
    ) {
    }

    public function getDescription(): ?string
    {
        return "Dropping table <b>{$this->tableName}</b>";
    }
}
