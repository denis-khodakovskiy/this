<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\Migrations\Command;

use This\ORM\Migrations\Schema\MigrationCommandInterface;

final readonly class DropColumnCommand implements MigrationCommandInterface
{
    public function __construct(
        private string $column,
    ) {
    }

    public function getColumn(): string
    {
        return $this->column;
    }

    public function getDescription(): ?string
    {
        return null;
    }
}
