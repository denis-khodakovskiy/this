<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\DQL\Compiled\Query;

use This\ORM\DQL\Compiled\Expression\CompiledExpressionInterface;
use This\ORM\DQL\Compiled\Expression\CompiledJoinExpression;

final class CompiledUpdate extends AbstractCompiledQuery
{
    /**
     * @param array<CompiledJoinExpression> $joins
     * @param array $set
     * @param CompiledExpressionInterface|null $where
     * @param array $params
     */
    public function __construct(
        public string $table,
        public string $alias,
        public array $joins,
        public array $set,
        public ?CompiledExpressionInterface $where,
        public array $params,
        public bool $all,
    ) {
    }
}
