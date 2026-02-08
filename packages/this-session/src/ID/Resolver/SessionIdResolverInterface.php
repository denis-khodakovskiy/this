<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Session\ID\Resolver;

interface SessionIdResolverInterface
{
    public function resolve(): string;

    public function clear(): void;
}
