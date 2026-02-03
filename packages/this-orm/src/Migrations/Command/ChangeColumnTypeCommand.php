<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\Migrations\Command;

use This\ORM\Migrations\Schema\MigrationCommandInterface;

final class ChangeColumnTypeCommand implements MigrationCommandInterface
{
    private ?string $type = null;

    public function __construct(
        private readonly string $column,
    ) {
    }

    public function getColumn(): string
    {
        return $this->column;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function type(?string $to): self
    {
        $this->type = $to;

        return $this;
    }

    public function getDescription(): ?string
    {
        return null;
    }
}
