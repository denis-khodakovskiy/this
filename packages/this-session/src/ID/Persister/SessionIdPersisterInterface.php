<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Session\ID\Persister;

interface SessionIdPersisterInterface
{
    public function persist(string $sessionId): void;

    public function clear(): void;
}
