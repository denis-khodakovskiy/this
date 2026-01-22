<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Cache\Transport;

use This\Cache\Contracts\CacheStorageInterface;

final readonly class MemcachedStorage implements CacheStorageInterface
{
    public function __construct(
        private \Memcached $memcached
    ) {
    }

    public function get($key, $default = null): mixed
    {
        $value = $this->memcached->get($key);

        if ($this->memcached->getResultCode() === \Memcached::RES_SUCCESS) {
            return $value;
        }

        return $default;
    }

    public function set($key, $value, $ttl = null): bool
    {
        if ($ttl === null) {
            return $this->memcached->set($key, $value);
        }

        return $this->memcached->set($key, $value, (int) $ttl);
    }

    public function has($key): bool
    {
        $this->memcached->get($key);

        return $this->memcached->getResultCode() === \Memcached::RES_SUCCESS;
    }

    public function delete($key): bool
    {
        return $this->memcached->delete($key);
    }

    public function clear(): bool
    {
        return $this->memcached->flush();
    }

    public function getMultiple($keys, $default = null): iterable
    {
        $result = [];

        foreach ($keys as $key) {
            $result[$key] = $this->get($key, $default);
        }

        return $result;
    }

    public function setMultiple($values, $ttl = null): bool
    {
        foreach ($values as $key => $value) {
            if (!$this->set($key, $value, $ttl)) {
                return false;
            }
        }

        return true;
    }

    public function deleteMultiple($keys): bool
    {
        foreach ($keys as $key) {
            $this->delete($key);
        }

        return true;
    }
}
