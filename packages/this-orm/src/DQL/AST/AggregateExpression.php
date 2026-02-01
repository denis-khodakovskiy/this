<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\DQL\AST;

final readonly class AggregateExpression implements ExpressionNode
{
    public function __construct(
        public string $function,
        public string $field,
        public ?string $alias = null,
    ) {
    }
}
