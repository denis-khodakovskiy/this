<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Cache;

use Psr\SimpleCache\InvalidArgumentException;
use This\Cache\Contracts\CacheInterface;
use This\Cache\Contracts\CacheStorageInterface;

final readonly class Cache implements CacheInterface
{
    public function __construct(
        private CacheStorageInterface $storage,
    ) {
    }

    /**
     * @throws InvalidArgumentException
     */
    public function get(string $key, callable $resolver, ?int $ttl = null, array $options = []): mixed
    {
        if ($this->storage->has($key)) {
            return $this->storage->get($key);
        }

        $value = $resolver();

        $this->storage->set($key, $value, $ttl);

        return $value;
    }
}
