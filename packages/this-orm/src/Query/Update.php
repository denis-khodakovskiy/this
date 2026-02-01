<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\Query;

use This\ORM\DQL\AST\SetExpression;

final class Update extends AbstractQuery
{
    /** @var array<SetExpression> */
    private array $set = [];

    private bool $all = false;

    private function __construct(string $schemaClass, ?string $alias = null)
    {
        $this->schemaClass = $schemaClass;
        $this->alias = $alias;
    }

    public static function table(string $schemaClass, ?string $alias = null): self
    {
        return new self($schemaClass, $alias);
    }

    public function set(string|array $field, mixed $value = null): self
    {
        if (is_array($field)) {
            foreach ($field as $f => $v) {
                $this->set[] = new SetExpression($f, $v);
            }
        } else {
            $this->set[] = new SetExpression($field, $value);
        }

        return $this;
    }

    public function getSet(): array
    {
        return $this->set;
    }

    public function all(): self
    {
        $this->all = true;

        return $this;
    }

    public function getAll(): bool
    {
        return $this->all;
    }
}
