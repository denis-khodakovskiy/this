<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Session\Container;

use This\Contracts\ContainerInterface;
use This\Session\ID\Generator\RandomSessionIdGenerator;
use This\Session\ID\Generator\SessionIdGeneratorInterface;
use This\Session\ID\Persister\CookieSessionIdPersister;
use This\Session\ID\Persister\SessionIdPersisterInterface;
use This\Session\ID\Resolver\CookieSessionIdResolver;
use This\Session\ID\Resolver\SessionIdResolverInterface;
use This\Session\Middleware\SessionMiddleware;
use This\Session\Runtime\PHPSessionRuntime;
use This\Session\Runtime\SessionRuntimeInterface;
use This\Session\SessionManager;
use This\Session\SessionManagerInterface;
use This\Session\Storage\PhpSessionStorage;
use This\Session\Storage\SessionStorageInterface;

final class SessionContainer
{
    public static function register(): \Closure
    {
        return static function (ContainerInterface $container) {
            $container
                ->bind(
                    id: SessionIdGeneratorInterface::class,
                    definition: static fn () => new RandomSessionIdGenerator(),
                    priority: 100,
                )
                ->bind(
                    id: SessionIdPersisterInterface::class,
                    definition: static fn () => new CookieSessionIdPersister(),
                    priority: 100,
                )
                ->bind(
                    id: SessionIdResolverInterface::class,
                    definition: static fn () => new CookieSessionIdResolver(
                        $container->get(id: SessionIdGeneratorInterface::class),
                        $container->get(id: SessionIdPersisterInterface::class),
                    ),
                    priority: 100,
                )
                ->bind(
                    id: SessionStorageInterface::class,
                    definition: static fn () => new PhpSessionStorage(),
                    priority: 100,
                )
                ->bind(
                    id: SessionManagerInterface::class,
                    definition: static fn () => new SessionManager(
                        $container->get(id: SessionStorageInterface::class),
                        $container->get(id: SessionIdResolverInterface::class),
                    ),
                    priority: 100,
                )
                ->bind(
                    id: SessionRuntimeInterface::class,
                    definition: static fn () => new PHPSessionRuntime(),
                    priority: 100,
                )
                ->bind(
                    id: SessionMiddleware::class,
                    definition: static fn () => new SessionMiddleware(),
                    priority: 100,
                )
            ;
        };
    }
}
