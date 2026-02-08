<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Session\ID\Persister;

final readonly class CookieSessionIdPersister implements SessionIdPersisterInterface
{
    public function __construct(
        private string $cookieName = 'SID',
        private array $options = [],
    ) {
    }

    public function persist(string $sessionId): void
    {
        setcookie(
            $this->cookieName,
            $sessionId,
            [
                'path'     => '/',
                'httponly' => true,
                'secure'   => true,
                'samesite' => 'Lax',
            ] + $this->options
        );
    }

    public function clear(): void
    {
        setcookie(
            $this->cookieName,
            '',
            ['expires' => time() - 3600]
        );
    }
}
