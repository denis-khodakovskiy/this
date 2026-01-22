<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Cache\Contracts;

interface CacheInterface
{
    public function get(string $key, callable $resolver, ?int $ttl = null, array $options = []): mixed;
}
