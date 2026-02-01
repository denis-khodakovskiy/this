<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\DQL\AST;

final readonly class ComparisonExpression implements ExpressionNode
{
    public function __construct(
        public string $field,
        public ComparisonOperatorEnum $operator,
        public mixed $value,
    ) {
    }
}
