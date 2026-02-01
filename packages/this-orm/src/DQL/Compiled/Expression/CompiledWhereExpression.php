<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\DQL\Compiled\Expression;

final readonly class CompiledWhereExpression
{
    /**
     * @param array<CompiledExpressionInterface> $predicates
     */
    public function __construct(
        public array $predicates,
    ) {
    }
}
