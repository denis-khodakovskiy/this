<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\Compiler;

use This\ORM\DQL\AST\BetweenExpression;
use This\ORM\DQL\AST\ColumnRefExpression;
use This\ORM\DQL\AST\ExpressionNode;
use This\ORM\DQL\AST\ComparisonExpression;
use This\ORM\DQL\AST\InExpression;
use This\ORM\DQL\AST\NullCheckExpression;
use This\ORM\DQL\AST\LogicalExpression;
use This\ORM\DQL\AST\NotExpression;
use This\ORM\DQL\AST\AlwaysTrueExpression;
use This\ORM\DQL\AST\AlwaysFalseExpression;
use This\ORM\DQL\AST\SetExpression;
use This\ORM\DQL\Compiled\Expression\CompiledBetweenExpression;
use This\ORM\DQL\Compiled\Expression\CompiledExpressionInterface;
use This\ORM\DQL\Compiled\Expression\CompiledBinaryExpression;
use This\ORM\DQL\Compiled\Expression\CompiledInExpression;
use This\ORM\DQL\Compiled\Expression\CompiledNullCheckExpression;
use This\ORM\DQL\Compiled\Expression\CompiledLogicalExpression;
use This\ORM\DQL\Compiled\Expression\CompiledAlwaysTrueExpression;
use This\ORM\DQL\Compiled\Expression\CompiledAlwaysFalseExpression;
use This\ORM\DQL\AST\RawExpression;
use This\ORM\DQL\Compiled\Expression\CompiledSetExpression;
use This\ORM\DQL\Compiled\Token\ColumnToken;
use This\ORM\DQL\Compiled\Token\ParamToken;
use This\ORM\DQL\Compiled\Token\RawToken;

final class ExpressionCompiler
{
    public function compile(ExpressionNode $expression, ParametersBag $parametersBag): CompiledExpressionInterface
    {
        return match (true) {
            $expression instanceof ComparisonExpression  => $this->compileComparison($expression, $parametersBag),
            $expression instanceof InExpression          => $this->compileIn($expression, $parametersBag),
            $expression instanceof NullCheckExpression   => $this->compileNullCheck($expression),
            $expression instanceof LogicalExpression     => $this->compileLogical($expression, $parametersBag),
            $expression instanceof NotExpression         => $this->compileNot($expression, $parametersBag),
            $expression instanceof BetweenExpression     => $this->compileBetween($expression, $parametersBag),
            $expression instanceof AlwaysTrueExpression  => new CompiledAlwaysTrueExpression(),
            $expression instanceof AlwaysFalseExpression => new CompiledAlwaysFalseExpression(),
            $expression instanceof SetExpression         => $this->compileSet($expression, $parametersBag),
            default => throw new \LogicException(
                'Unsupported expression: ' . get_class($expression)
            ),
        };
    }

    private function compileComparison(
        ComparisonExpression $expression,
        ParametersBag $parametersBag,
    ): CompiledExpressionInterface {
        $token = match (true) {
            $expression->value instanceof RawExpression => new RawToken($expression->value->expression),
            $expression->value instanceof ColumnRefExpression => new ColumnToken($expression->value->columnReference),
            default => new ParamToken($parametersBag->next($expression->value)),
        };

        return new CompiledBinaryExpression(
            field: $expression->field,
            operator: $expression->operator->value,
            token: $token,
        );
    }

    private function compileIn(
        InExpression $expression,
        ParametersBag $parametersBag,
    ): CompiledExpressionInterface {
        if ($expression->values === []) {
            return new CompiledAlwaysFalseExpression();
        }

        $tokens = [];

        foreach ($expression->values as $value) {
            $tokens[] = new ParamToken($parametersBag->next($value));
        }

        return new CompiledInExpression(
            field: $expression->field,
            tokens: $tokens,
            negated: $expression->negated,
        );
    }

    private function compileBetween(
        BetweenExpression $expression,
        ParametersBag $parametersBag,
    ): CompiledExpressionInterface {
        return new CompiledBetweenExpression(
            field: $expression->field,
            min: $parametersBag->next($expression->min),
            max: $parametersBag->next($expression->max),
            negated: $expression->negated,
        );
    }

    private function compileNullCheck(
        NullCheckExpression $expression
    ): CompiledExpressionInterface {
        return new CompiledNullCheckExpression(
            field: $expression->field,
            negated: $expression->negated,
        );
    }

    private function compileLogical(
        LogicalExpression $expression,
        ParametersBag $parametersBag,
    ): CompiledExpressionInterface {
        return new CompiledLogicalExpression(
            operator: $expression->type->value,
            expressions: array_map(
                fn (ExpressionNode $node) => $this->compile($node, $parametersBag),
                $expression->expressions
            ),
        );
    }

    private function compileNot(
        NotExpression $expression,
        ParametersBag $parametersBag,
    ): CompiledExpressionInterface {
        $compiled = $this->compile($expression->expression, $parametersBag);

        return match (true) {
            $compiled instanceof CompiledAlwaysTrueExpression  => new CompiledAlwaysFalseExpression(),
            $compiled instanceof CompiledAlwaysFalseExpression => new CompiledAlwaysTrueExpression(),
            default => throw new \LogicException(
                'NOT expression is not supported for ' . get_class($compiled)
            ),
        };
    }

    private function compileSet(
        SetExpression $expression,
        ParametersBag $parametersBag,
    ): CompiledSetExpression {
        return new CompiledSetExpression(
            field: $expression->field,
            token: match (true) {
                $expression->value instanceof RawExpression => new RawToken($expression->value->expression),
                default => new ParamToken($parametersBag->next($expression->value)),
            },
        );
    }
}

