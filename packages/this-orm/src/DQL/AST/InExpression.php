<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\DQL\AST;

final readonly class InExpression implements ExpressionNode
{
    public function __construct(
        public string $field,
        public array $values,
        public bool $negated = false,
    ) {
    }
}
