<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\Migrations\Command;

use This\ORM\Migrations\Schema\MigrationCommandInterface;

final readonly class AddPrimaryKeyCommand implements MigrationCommandInterface
{
    public function __construct(
        private array $columns,
    ) {
    }

    public function getColumns(): array
    {
        return $this->columns;
    }

    public function getDescription(): ?string
    {
        return null;
    }
}
