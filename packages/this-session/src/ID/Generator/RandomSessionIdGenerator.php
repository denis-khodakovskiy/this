<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Session\ID\Generator;

use Random\RandomException;

final readonly class RandomSessionIdGenerator implements SessionIdGeneratorInterface
{
    public function __construct(
        private string $cookieName = 'SID',
    ) {
    }

    /**
     * @throws RandomException
     */
    public function generate(): string
    {
        if (isset($_COOKIE[$this->cookieName])) {
            return $_COOKIE[$this->cookieName];
        }

        return bin2hex(random_bytes(32));
    }
}
