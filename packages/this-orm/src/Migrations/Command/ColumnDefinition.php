<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\Migrations\Command;

final class ColumnDefinition
{
    private ?string $type = null;

    private ?int $length = null;

    private bool $nullable = false;

    private mixed $defaultValue = null;

    private mixed $defaultExpression = null;

    private bool $autoIncrement = false;

    private bool $primary = false;

    private ?string $comment = null;

    private ?string $after = null;

    private ?string $collation = null;

    private ?string $newName = null;

    public function __construct(
        private readonly string $name,
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function string(int $length = 255): self
    {
        $this->type = 'string';
        $this->length = $length;

        return $this;
    }

    public function int(): self
    {
        $this->type = 'int';

        return $this;
    }

    public function text(): self
    {
        $this->type = 'text';

        return $this;
    }

    public function bool(): self
    {
        $this->type = 'bool';

        return $this;
    }

    public function date(): self
    {
        $this->type = 'date';

        return $this;
    }

    public function dateTime(): self
    {
        $this->type = 'datetime';

        return $this;
    }

    public function timestamp(): self
    {
        $this->type = 'timestamp';

        return $this;
    }

    public function nullable(): self
    {
        $this->nullable = true;

        return $this;
    }

    public function defaultValue(mixed $value): self
    {
        $this->defaultValue = $value;

        return $this;
    }

    public function defaultExpression(string $value): self
    {
        $this->defaultExpression = $value;

        return $this;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function getLength(): ?int
    {
        return $this->length;
    }

    public function getDefaultValue(): mixed
    {
        return $this->defaultValue;
    }

    public function getDefaultExpression(): ?string
    {
        return $this->defaultExpression;
    }

    public function isNullable(): bool
    {
        return $this->nullable;
    }

    public function autoIncrement(): self
    {
        $this->autoIncrement = true;

        return $this;
    }

    public function isAutoIncrement(): bool
    {
        return $this->autoIncrement;
    }

    public function primary(): self
    {
        $this->primary = true;

        return $this;
    }

    public function isPrimary(): bool
    {
        return $this->primary;
    }

    public function comment(?string $comment): self
    {
        $this->comment = $comment;

        return $this;
    }

    public function getComment(): ?string
    {
        return $this->comment;
    }

    public function after(?string $column): self
    {
        $this->after = $column;

        return $this;
    }

    public function getAfter(): ?string
    {
        return $this->after;
    }

    public function collation(?string $collation): self
    {
        $this->collation = $collation;

        return $this;
    }

    public function getCollation(): ?string
    {
        return $this->collation;
    }

    public function renameTo(): self
    {
        $this->newName = $this->name;

        return $this;
    }

    public function getNewName(): ?string
    {
        return $this->newName;
    }
}
