<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App\This\Middlewares\Request;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use This\Contracts\RequestContextInterface;
use This\Contracts\RequestInterface;
use This\Contracts\RequestMetaCollectorInterface;
use This\Contracts\RequestProviderInterface;
use This\Contracts\RequestResolverInterface;
use This\Contracts\RequestResolversRegistryInterface;

class RequestInitMiddleware
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(RequestContextInterface $context, callable $next): void
    {
        /** @var RequestResolverInterface $requestResolver */
        $requestResolver = $context->getContainer()->get(RequestResolversRegistryInterface::class)->getResolver();
        $request = $requestResolver->resolve();
        $context->getContainer()->get(RequestMetaCollectorInterface::class)->set(RequestInterface::class, $request);

        $next($context);
    }
}
