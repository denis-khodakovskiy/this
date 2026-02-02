<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

use App\Handlers\CacheTestHandler;
use This\Cache\Cache;
use This\Cache\Contracts\CacheInterface;
use This\Cache\Transport\MemcachedStorage;
use This\Contracts\ContainerInterface;

return static function (ContainerInterface $container) {
    $container
        ->singleton(id: CacheInterface::class, definition: static fn () => new Cache(
            storage: new MemcachedStorage(new \Memcached()),
        ))
        ->bind(id: CacheTestHandler::class, definition: static fn () => new CacheTestHandler(
            $container->get(id: CacheInterface::class),
        ))
    ;
};
