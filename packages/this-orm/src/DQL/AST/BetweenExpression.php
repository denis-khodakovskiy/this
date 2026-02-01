<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\DQL\AST;

final readonly class BetweenExpression implements ExpressionNode
{
    public function __construct(
        public string $field,
        public mixed $min,
        public mixed $max,
        public bool $negated = false,
    ) {
    }
}
