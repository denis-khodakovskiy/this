<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Cache\Container;

use This\Cache\Cache;
use This\Cache\CacheInterface;
use This\Cache\Storage\MemcachedStorage;
use This\Contracts\ContainerInterface;

final class CacheContainer
{
    public static function register(): \Closure
    {
        return static function (ContainerInterface $container) {
            $container
                ->singleton(id: CacheInterface::class, definition: static fn() => new Cache(
                    storage: new MemcachedStorage(new \Memcached()),
                ))
            ;
        };
    }
}
