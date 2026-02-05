<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\Migrations\Command;

use This\ORM\Migrations\Schema\MigrationCommandInterface;

final class CreateIndexCommand implements MigrationCommandInterface
{
    private ?string $name = null;

    private bool $unique = false;

    public function __construct(
        private readonly string $table,
        private readonly array $columns,
    ) {
    }

    public function getColumns(): array
    {
        return $this->columns;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getTable(): string
    {
        return $this->table;
    }

    public function unique(): self
    {
        $this->unique = true;

        return $this;
    }

    public function isUnique(): bool
    {
        return $this->unique;
    }

    public function getDescription(): ?string
    {
        return sprintf(
            'Creating index <b>(%s)</b>',
            implode(', ', $this->columns),
        );
    }
}
