<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\DQL\Compiled\Expression;

use This\ORM\DQL\AST\LogicalTypeEnum;

final readonly class CompiledLogicalExpression implements CompiledExpressionInterface
{
    /**
     * @param array<CompiledExpressionInterface> $expressions
     */
    public function __construct(
        public string $operator,
        public array $expressions,
    ) {
    }
}
