<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\Migrations\Command;

use This\ORM\Migrations\Schema\MigrationCommandInterface;

final class RenameColumnCommand implements MigrationCommandInterface
{
    private ?string $to = null;

    public function __construct(
        private readonly string $column,
    ) {
    }

    public function getColumn(): string
    {
        return $this->column;
    }

    public function getTo(): ?string
    {
        return $this->to;
    }

    public function to(?string $to): self
    {
        $this->to = $to;

        return $this;
    }

    public function getDescription(): ?string
    {
        return null;
    }
}
