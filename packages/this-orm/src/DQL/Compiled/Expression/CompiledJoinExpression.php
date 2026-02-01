<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\DQL\Compiled\Expression;

use This\ORM\DQL\AST\JoinTypeEnum;

final readonly class CompiledJoinExpression implements CompiledExpressionInterface
{
    public function __construct(
        public string $tableName,
        public string $alias,
        public CompiledExpressionInterface $on,
        public string $type,
    ) {
    }
}
