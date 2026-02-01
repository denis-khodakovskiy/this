<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\DQL\Compiled\Expression;

use This\ORM\DQL\Compiled\Token\CompiledToken;

final class CompiledInsertRowExpression
{
    /**
     * @param array<CompiledToken> $values
     */
    public function __construct(
        public array $values,
    ) {
    }
}
