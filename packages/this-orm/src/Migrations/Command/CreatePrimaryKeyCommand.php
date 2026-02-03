<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\Migrations\Command;

use This\ORM\Migrations\Schema\MigrationCommandInterface;

final readonly class CreatePrimaryKeyCommand implements MigrationCommandInterface
{
    public function __construct(
        public string $table,
        public array $columns,
    ) {
    }

    public function getDescription(): ?string
    {
        return null;
    }
}
