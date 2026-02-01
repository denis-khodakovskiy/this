<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\DQL\Compiled\Expression;

final readonly class CompiledNullCheckExpression implements CompiledExpressionInterface
{
    public function __construct(
        public string $field,
        public bool $negated,
    ) {
    }
}
