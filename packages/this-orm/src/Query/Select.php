<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\Query;

use This\ORM\DQL\AST\AggregateExpression;
use This\ORM\DQL\AST\ExpressionNode;
use This\ORM\DQL\AST\JoinExpression;
use This\ORM\DQL\AST\JoinTypeEnum;
use This\ORM\DQL\Expr;

final class Select extends AbstractQuery
{
    /** @var array<non-empty-string> */
    private array $select = ['*'];

    /** @var array{field: string, dir: 'ASC'|'DESC'} */
    private array $orderBy = [];

    /** @var array<non-empty-string> */
    private array $groupBy = [];

    private ?ExpressionNode $having = null;

    private ?int $limit = null;

    private ?int $offset = null;

    private function __construct(string $schemaClass, ?string $alias = null)
    {
        $this->schemaClass = $schemaClass;
        $this->alias = $alias;
    }

    public static function from(string $schemaClass, ?string $alias = null): self
    {
        return new self($schemaClass, $alias);
    }

    public function select(string|AggregateExpression ...$fields): self
    {
        $this->select = $fields;

        return $this;
    }

    public function getSelect(): array
    {
        return $this->select;
    }

    public function orderBy(array $order): self
    {
        $this->orderBy = $order;

        return $this;
    }

    public function getOrderBy(): array
    {
        return $this->orderBy;
    }

    public function limit(int $limit): self
    {
        $this->limit = $limit;

        return $this;
    }

    public function getLimit(): ?int
    {
        return $this->limit;
    }

    public function offset(int $offset): self
    {
        $this->offset = $offset;

        return $this;
    }

    public function getOffset(): ?int
    {
        return $this->offset;
    }

    public function groupBy(...$groupBy): self
    {
        $this->groupBy = $groupBy;

        return $this;
    }

    public function getGroupBy(): array
    {
        return $this->groupBy;
    }

    public function having(ExpressionNode ...$expressions): self
    {
        if ($expressions !== []) {
            $this->having = count($expressions) === 1
                ? $expressions[0]
                : Expr::and(...$expressions);
        }

        return $this;
    }

    public function getHaving(): ?ExpressionNode
    {
        return $this->having;
    }

    public function rightJoin(
        string $schemaClass,
        string $alias,
        ExpressionNode $on,
    ): self {
        $this->joins[] = new JoinExpression(schemaClass: $schemaClass, alias: $alias, on: $on, type: JoinTypeEnum::RIGHT);

        return $this;
    }

    public function outerJoin(
        string $schemaClass,
        string $alias,
        ExpressionNode $on,
    ): self {
        $this->joins[] = new JoinExpression(schemaClass: $schemaClass, alias: $alias, on: $on, type: JoinTypeEnum::OUTER);

        return $this;
    }

    public function fullJoin(
        string $schemaClass,
        string $alias,
        ExpressionNode $on,
    ): self {
        $this->joins[] = new JoinExpression(schemaClass: $schemaClass, alias: $alias, on: $on, type: JoinTypeEnum::FULL);

        return $this;
    }
}
