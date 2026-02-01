<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\DQL\AST;

final class LogicalExpression implements ExpressionNode
{
    /**
     * @param array<ExpressionNode> $expressions
     */
    public function __construct(
        public LogicalTypeEnum $type,
        public array $expressions
    ) {
    }
}
