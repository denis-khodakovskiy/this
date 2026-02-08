<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Security\Container;

use This\Contracts\ContainerInterface;
use This\Contracts\RequestMetaCollectorInterface;
use This\Security\Middleware\SecurityMiddleware;
use This\Security\Provider\UserIdentityResolver;
use This\Security\Provider\UserIdentityResolverInterface;
use This\Security\Security;
use This\Security\SecurityInterface;

final class SecurityContainer
{
    public static function register(): \Closure
    {
        return static function (ContainerInterface $container) {
            $container
                ->singleton(
                    id: SecurityInterface::class,
                    definition:  static fn () => new Security(
                        $container->get(RequestMetaCollectorInterface::class),
                    ),
                    priority: 100,
                )
                ->bind(
                    id: UserIdentityResolverInterface::class,
                    definition: static fn () => new UserIdentityResolver(),
                    priority: 100,
                )
                ->bind(
                    id: SecurityMiddleware::class,
                    definition: static fn() => new SecurityMiddleware(),
                    priority: 100,
                )
            ;
        };
    }
}
