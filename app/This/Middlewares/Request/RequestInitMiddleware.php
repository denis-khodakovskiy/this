<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App\This\Middlewares\Request;

use This\Contracts\ContextInterface;
use This\Contracts\RequestProviderInterface;
use This\Contracts\RequestResolverInterface;
use This\Contracts\RequestResolversRegistryInterface;

class RequestInitMiddleware
{
    public function __invoke(ContextInterface $context, callable $next): void
    {
        /** @var RequestResolverInterface $requestResolver */
        $requestResolver = $context->getContainer()->get(RequestResolversRegistryInterface::class)->getResolver();
        $request = $requestResolver->resolve();
        $context->setRequest($request);

        /** @var RequestProviderInterface $requestProvider */
        $requestProvider = $context->getContainer()->get(RequestProviderInterface::class);
        $requestProvider->setRequest($request);

        $next($context);
    }
}
