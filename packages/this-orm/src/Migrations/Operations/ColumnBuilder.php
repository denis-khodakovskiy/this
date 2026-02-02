<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\Migrations\Operations;

use This\ORM\Migrations\Schema\CommandInterface;

final class ColumnBuilder implements CommandInterface
{
    private ?string $type = null;

    private ?int $length = null;

    private bool $nullable = false;

    private mixed $defaultValue = null;

    private mixed $defaultExpression = null;

    public function __construct(
        private string $name,
    ) {
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
}
