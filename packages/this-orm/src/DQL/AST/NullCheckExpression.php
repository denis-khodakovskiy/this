<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\DQL\AST;

final readonly class NullCheckExpression implements ExpressionNode
{
    public function __construct(
        public string $field,
        public bool $negated = false,
    ) {
    }
}