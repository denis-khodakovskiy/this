<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Session\Middleware;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use This\Contracts\RequestContextInterface;
use This\Session\Runtime\SessionRuntimeInterface;

final class SessionMiddleware
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(RequestContextInterface $context, callable $next): void
    {
        $runtime = $context->getContainer()->get(id: SessionRuntimeInterface::class);

        assert($runtime instanceof SessionRuntimeInterface);

        $runtime->boot();;

        $next($context);
    }
}
