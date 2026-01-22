<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App\Handlers;

use This\Cache\Contracts\CacheInterface;

final readonly class CacheTestHandler
{
    public function __construct(
        private CacheInterface $cache,
    ) {
    }

    public function __invoke(): void
    {
        $key = 'key';

        var_dump($this->cache->get(
            'test',
            static function () use ($key) {
                return $key;
            },
            300
        ));
    }
}
