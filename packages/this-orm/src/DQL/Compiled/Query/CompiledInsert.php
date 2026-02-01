<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\DQL\Compiled\Query;

use This\ORM\DQL\Compiled\Expression\CompiledInsertRowExpression;

final class CompiledInsert extends AbstractCompiledQuery
{
    /**
     * @param array<CompiledInsertRowExpression> $rows
     * @param array<non-empty-string, non-empty-string> $params
     */
    public function __construct(
        public string $table,
        public array $columns,
        public array $rows,
        public array $params,
    ) {
    }
}
