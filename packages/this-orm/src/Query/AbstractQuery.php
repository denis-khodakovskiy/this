<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\Query;

use This\ORM\DQL\AST\ExpressionNode;
use This\ORM\DQL\AST\JoinExpression;
use This\ORM\DQL\AST\JoinTypeEnum;
use This\ORM\DQL\Expr;

abstract class AbstractQuery
{
    protected string $schemaClass;

    protected ?string $alias = null;

    protected ?ExpressionNode $where = null;

    /**
     * @var array<JoinExpression>
     */
    protected array $joins = [];

    public function where(ExpressionNode ...$expressions): static
    {
        if ($expressions !== []) {
            $this->where = count($expressions) === 1
                ? $expressions[0]
                : Expr::and(...$expressions);
        }

        return $this;
    }

    public function innerJoin(
        string $schemaClass,
        string $alias,
        ExpressionNode $on,
    ): self {
        $this->joins[] = new JoinExpression(schemaClass: $schemaClass, alias: $alias, on: $on, type: JoinTypeEnum::INNER);

        return $this;
    }

    public function leftJoin(
        string $schemaClass,
        string $alias,
        ExpressionNode $on,
    ): self {
        $this->joins[] = new JoinExpression(schemaClass: $schemaClass, alias: $alias, on: $on, type: JoinTypeEnum::LEFT);

        return $this;
    }

    public function getSchemaClass(): string
    {
        return $this->schemaClass;
    }

    public function getAlias(): ?string
    {
        return $this->alias;
    }

    public function getWhere(): ?ExpressionNode
    {
        return $this->where;
    }

    public function getJoins(): array
    {
        return $this->joins;
    }
}
