<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\DQL\Compiled\Query;

use This\ORM\DQL\Compiled\Expression\CompiledExpressionInterface;
use This\ORM\DQL\Compiled\Expression\CompiledJoinExpression;

final class CompiledSelect extends AbstractCompiledQuery
{
    /**
     * @param array<array-key, non-empty-string> $select
     * @param array<CompiledJoinExpression> $joins
     * @param array<array-key, non-empty-string> $groupBy
     * @param array<non-empty-string, non-empty-string> $orderBy
     * @param array<non-empty-string, non-empty-string|numeric> $params
     */
    public function __construct(
        public string $fromTable,
        public string $alias,
        public array $select,
        public array $joins,
        public ?CompiledExpressionInterface $where,
        public ?CompiledExpressionInterface $having,
        public array $groupBy,
        public array $orderBy,
        public ?int $limit,
        public ?int $offset,
        public array $params,
    ) {
    }
}
