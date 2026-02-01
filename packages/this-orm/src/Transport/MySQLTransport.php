<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\Transport;

use This\ORM\DQL\AST\AggregateExpression;
use This\ORM\DQL\AST\LogicalTypeEnum;
use This\ORM\DQL\Compiled\Expression\CompiledAlwaysFalseExpression;
use This\ORM\DQL\Compiled\Expression\CompiledAlwaysTrueExpression;
use This\ORM\DQL\Compiled\Expression\CompiledBetweenExpression;
use This\ORM\DQL\Compiled\Expression\CompiledBinaryExpression;
use This\ORM\DQL\Compiled\Expression\CompiledExpressionInterface;
use This\ORM\DQL\Compiled\Expression\CompiledInExpression;
use This\ORM\DQL\Compiled\Expression\CompiledInsertRowExpression;
use This\ORM\DQL\Compiled\Expression\CompiledJoinExpression;
use This\ORM\DQL\Compiled\Expression\CompiledLogicalExpression;
use This\ORM\DQL\Compiled\Expression\CompiledNullCheckExpression;
use This\ORM\DQL\Compiled\Expression\CompiledSetExpression;
use This\ORM\DQL\Compiled\Query\CompiledDelete;
use This\ORM\DQL\Compiled\Query\CompiledInsert;
use This\ORM\DQL\Compiled\Query\CompiledSelect;
use This\ORM\DQL\Compiled\Query\CompiledUpdate;
use This\ORM\DQL\Compiled\Token\ColumnToken;
use This\ORM\DQL\Compiled\Token\CompiledToken;

final class MySQLTransport implements TransportInterface
{
    public function prepareSelect(CompiledSelect $query): string
    {
        $parts = [
            'SELECT ' . implode(', ', array_map(
                function (string|AggregateExpression $node) {
                    return $node instanceof AggregateExpression
                        ? "{$node->function}({$this->parseName($node->field)})" . ($node->alias ? " AS `{$node->alias}`" : '')
                        : $this->parseName($node);
                },
                $query->select,
            )) . " FROM {$this->parseName($query->fromTable)} `{$query->alias}`",
        ];

        if ($query->joins) {
            foreach ($query->joins as $join) {
                $parts[] = $this->compile($join);
            }
        }

        if ($query->where) {
            $parts[] = 'WHERE ' . $this->compile($query->where);
        }

        if ($query->groupBy) {
            $parts[] = 'GROUP BY ' . implode(', ', array_map(
                fn (string $field) => $this->parseName($field),
                $query->groupBy,
            ));
        }

        if ($query->having) {
            $parts[] = 'HAVING ' . $this->compile($query->having);
        }

        if ($query->orderBy) {
            $order = [];

            foreach ($query->orderBy as $field => $direction) {
                $order[] = "{$this->parseName($field)} {$direction}";
            }

            $parts[] = 'ORDER BY ' . implode(', ', $order);
        }

        if ($query->limit) {
            $parts[] = 'LIMIT ' . $query->limit;
        }

        if ($query->offset) {
            $parts[] = 'OFFSET ' . $query->offset;
        }

        return implode("\n", $parts);
    }

    public function prepareInsert(CompiledInsert $query): string
    {
        return 'INSERT INTO ' . $this->parseName($query->table) . ' (' . implode(', ', array_map(
            fn (string $field) => $this->parseName($field),
            $query->columns,
        )) . ") VALUES\n" . implode(",\n", array_map(
            static fn (CompiledInsertRowExpression $expression) => '(' . implode(', ', array_map(
                static fn (CompiledToken $token) => $token->token,
                $expression->values,
            )) . ')',
            $query->rows,
        ));
    }

    public function prepareUpdate(CompiledUpdate $query): string
    {
        if (!$query->where && !$query->all) {
            throw new \LogicException(
                'UPDATE without WHERE requires explicit ->all()'
            );
        }

        $parts = [
            "UPDATE {$this->parseName($query->table)} `{$query->alias}`",
        ];

        if ($query->joins) {
            foreach ($query->joins as $join) {
                $parts[] = $this->compile($join);
            }
        }

        $parts[] = 'SET ' . implode(', ', array_map(
            fn (CompiledSetExpression $setExpression) => $this->compile($setExpression),
            $query->set,
        ));

        if ($query->where) {
            $parts[] = 'WHERE ' . $this->compile($query->where);
        }

        return implode("\n", $parts);
    }

    public function prepareDelete(CompiledDelete $query): string
    {
        if (!$query->where && !$query->all) {
            throw new \LogicException(
                'DELETE without WHERE requires explicit ->all()'
            );
        }

        $parts = [
            "DELETE `{$query->alias}` FROM " . $this->parseName($query->table) . " `{$query->alias}`",
        ];

        if ($query->joins) {
            foreach ($query->joins as $join) {
                $parts[] = $this->compile($join);
            }
        }

        if ($query->where) {
            $parts[] = 'WHERE ' . $this->compile($query->where);
        }

        return implode("\n", $parts);
    }

    private function compile(CompiledExpressionInterface $compiledExpression): string
    {
        return match (true) {
            $compiledExpression instanceof CompiledBinaryExpression => $this->compileBinaryExpression($compiledExpression),
            $compiledExpression instanceof CompiledInExpression => $this->compileInExpression($compiledExpression),
            $compiledExpression instanceof CompiledAlwaysFalseExpression => $this->compileAlwaysFalseExpression(),
            $compiledExpression instanceof CompiledAlwaysTrueExpression => $this->compileAlwaysTrueExpression(),
            $compiledExpression instanceof CompiledJoinExpression => $this->compileJoinExpression($compiledExpression),
            $compiledExpression instanceof CompiledLogicalExpression => $this->compileLogicalExpression($compiledExpression),
            $compiledExpression instanceof CompiledNullCheckExpression => $this->compileNullCheckExpression($compiledExpression),
            $compiledExpression instanceof CompiledBetweenExpression => $this->compileBetweenExpression($compiledExpression),
            $compiledExpression instanceof CompiledSetExpression => $this->compileSetExpression($compiledExpression),
            default => throw new \LogicException(
                'Unsupported expression: ' . get_class($compiledExpression)
            ),
        };
    }

    private function compileBinaryExpression(CompiledBinaryExpression $expr): string
    {
        return implode(' ', [
            $this->parseName($expr->field),
            $expr->operator,
            $expr->token instanceof ColumnToken
                ? $this->parseName($expr->token->token)
                : $expr->token->token,
        ]);
    }

    private function compileInExpression(CompiledInExpression $expr): string
    {
        return implode(' ', [
            $this->parseName($expr->field),
            $expr->negated ? 'NOT IN' : 'IN',
            '(' . implode(', ', array_map(
                static fn (CompiledToken $token) => $token->token,
                $expr->tokens,
            )) . ')',
        ]);
    }

    private function compileAlwaysFalseExpression(): string
    {
        return '1 = 0';
    }

    private function compileAlwaysTrueExpression(): string
    {
        return '1 = 1';
    }

    private function compileJoinExpression(CompiledJoinExpression $expr): string
    {
        return implode(' ', [
            $expr->type,
            'JOIN',
            $this->parseName($expr->tableName),
            "`{$expr->alias}`",
            'ON',
            $this->compile($expr->on),
        ]);
    }

    private function compileLogicalExpression(CompiledLogicalExpression $expr): string
    {
        $expression = implode(" {$expr->operator} ", array_map(
            fn (CompiledExpressionInterface $compiledExpression) => $this->compile($compiledExpression),
            $expr->expressions,
        ));

        return $expr->operator === LogicalTypeEnum::OR->value
            ? "($expression)"
            : $expression;
    }

    private function compileNullCheckExpression(CompiledNullCheckExpression $expr): string
    {
        return "{$this->parseName($expr->field)} IS" . ($expr->negated ? ' NOT' : '') . ' NULL';
    }

    private function compileBetweenExpression(CompiledBetweenExpression $expr): string
    {
        return implode(' ', [
            $this->parseName($expr->field),
            $expr->negated ? 'NOT BETWEEN' : 'BETWEEN',
            $expr->min,
            'AND',
            $expr->max,
        ]);
    }

    private function compileSetExpression(CompiledSetExpression $expr): string
    {
        return "{$this->parseName($expr->field)} = " . match (true) {
            $expr->token instanceof ColumnToken => $this->parseName($expr->token->token),
            default => $expr->token->token,
        };
    }

    private function parseName(string $field): string
    {
        if (!str_contains($field, '.')) {
            return "`$field`";
        }

        return implode('.', array_map(
            static fn (string $part) => "`{$part}`",
            explode('.', $field),
        ));
    }
}
