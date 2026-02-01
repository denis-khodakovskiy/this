<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\DQL\Compiled\Token;

abstract class CompiledToken
{
    public function __construct(
        public readonly string $token,
    ) {
    }
}
