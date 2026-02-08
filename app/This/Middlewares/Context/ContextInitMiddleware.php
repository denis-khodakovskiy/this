<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App\This\Middlewares\Context;

use Random\RandomException;
use This\Contracts\RequestContextInterface;
use This\Contracts\MiddlewareInterface;

class ContextInitMiddleware implements MiddlewareInterface
{
    /**
     * @throws RandomException
     */
    public function __invoke(RequestContextInterface $context, callable $next): void
    {
        $context
//            ->addMeta(key: 'startedAt', value: time())
//            ->addMeta(key: 'environment', value: 'dev')
//            ->addMeta(key: 'id', value: md5((string) microtime(true)))
            ->setIsCli(PHP_SAPI === 'cli' || PHP_SAPI === 'phpdbg')
            ->setIsHttp(PHP_SAPI !== 'cli' && PHP_SAPI !== 'phpdbg')
        ;

        $next($context);
    }
}
