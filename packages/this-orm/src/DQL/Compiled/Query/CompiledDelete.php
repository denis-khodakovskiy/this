<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\DQL\Compiled\Query;

use This\ORM\DQL\Compiled\Expression\CompiledExpressionInterface;

final class CompiledDelete extends AbstractCompiledQuery
{
    public function __construct(
        public string $table,
        public string $alias,
        public array $joins,
        public ?CompiledExpressionInterface $where,
        public array $params,
        public bool $all,
    ) {
    }
}
