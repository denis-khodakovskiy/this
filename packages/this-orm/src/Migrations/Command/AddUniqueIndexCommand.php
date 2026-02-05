<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\Migrations\Command;

use This\ORM\Migrations\Schema\MigrationCommandInterface;

final class AddUniqueIndexCommand implements MigrationCommandInterface
{
    private ?string $name = null;

    public function __construct(
        private readonly string $tableName,
        private readonly array $columns,
    ) {
    }

    public function getColumns(): array
    {
        return $this->columns;
    }

    public function getTableName(): string
    {
        return $this->tableName;
    }

    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function getDescription(): ?string
    {
        return sprintf(
            'Adding unique index <b>%s</b> for columns <b>%s</b>',
            $this->name,
            implode(', ', $this->columns)
        );
    }
}