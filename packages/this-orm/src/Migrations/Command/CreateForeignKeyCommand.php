<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\Migrations\Command;

use This\ORM\Migrations\Schema\MigrationCommandInterface;

final class CreateForeignKeyCommand implements MigrationCommandInterface
{
    private string $name;

    private string $onDelete = 'NO ACTION';

    private string $onUpdate = 'NO ACTION';

    public function __construct(
        private readonly string $table,
        private readonly string $column,
        private readonly string $referenceTable,
        private readonly string $referenceColumn,
    ) {
        $this->name = sprintf('fk_%s_%s_%s_%s', $table, $column, $referenceTable, $referenceColumn);
    }

    public function onDelete(string $onDelete): self
    {
        $this->onDelete = $onDelete;

        return $this;
    }

    public function onUpdate(string $onUpdate): self
    {
        $this->onUpdate = $onUpdate;

        return $this;
    }

    public function getTable(): string
    {
        return $this->table;
    }

    public function getColumn(): string
    {
        return $this->column;
    }

    public function getReferenceTable(): string
    {
        return $this->referenceTable;
    }

    public function getReferenceColumn(): string
    {
        return $this->referenceColumn;
    }

    public function getOnDelete(): string
    {
        return $this->onDelete;
    }

    public function getOnUpdate(): string
    {
        return $this->onUpdate;
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return sprintf(
            'Creating foreign key <b>%s</b> reference to <b>%s</b>.<b>%s</b>',
            $this->column,
            $this->referenceTable,
            $this->referenceColumn,
        );
    }
}
