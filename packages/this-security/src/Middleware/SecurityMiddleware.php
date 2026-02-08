<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Security\Middleware;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use This\Contracts\RequestContextInterface;
use This\Security\Identity\UserIdentity;
use This\Security\Provider\UserIdentityResolverInterface;

final class SecurityMiddleware
{
    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __invoke(RequestContextInterface $context, callable $next): void
    {
        /** @var UserIdentityResolverInterface $identityProvider */
        $identityProvider = $context->getContainer()->get(id: UserIdentityResolverInterface::class);
        $context->addMeta(UserIdentity::class, $identityProvider->resolveIdentity($context));

        $next($context);
    }
}
