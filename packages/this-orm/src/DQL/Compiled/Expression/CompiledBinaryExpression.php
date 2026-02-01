<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\DQL\Compiled\Expression;

use This\ORM\DQL\Compiled\Token\CompiledToken;

final readonly class CompiledBinaryExpression implements CompiledExpressionInterface
{
    public function __construct(
        public string $field,
        public string $operator,
        public CompiledToken $token,
    ) {
    }
}
