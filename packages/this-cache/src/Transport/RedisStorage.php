<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Cache\Transport;

use Redis;
use This\Cache\Contracts\CacheStorageInterface;

final readonly class RedisStorage implements CacheStorageInterface
{
    public function __construct(
        private Redis $redis,
    ) {
    }

    /**
     * @throws \RedisException
     */
    public function get(string $key, mixed $default = null): mixed
    {
        return $this->redis->get($key);
    }

    /**
     * @throws \RedisException
     */
    public function set(string $key, mixed $value, \DateInterval|int|null $ttl = null): bool
    {
        return $this->redis->set($key, $value, $ttl);
    }

    /**
     * @throws \RedisException
     */
    public function delete(string $key): bool
    {
        return $this->redis->del($key);
    }

    /**
     * @throws \RedisException
     */
    public function clear(): bool
    {
        return $this->redis->flushAll();
    }

    /**
     * @throws \RedisException
     */
    public function getMultiple(iterable $keys, mixed $default = null): iterable
    {
        return $this->redis->mget((array) $keys);
    }

    /**
     * @throws \RedisException
     */
    public function setMultiple(iterable $values, \DateInterval|int|null $ttl = null): bool
    {
        return $this->redis->mset((array) $values);
    }

    /**
     * @throws \RedisException
     */
    public function deleteMultiple(iterable $keys): bool
    {
        return $this->redis->del((array) $keys);
    }

    /**
     * @throws \RedisException
     */
    public function has(string $key): bool
    {
        return $this->redis->exists($key);
    }
}