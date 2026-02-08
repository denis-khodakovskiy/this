<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App\This\Middlewares\Request;

use This\Contracts\RequestContextInterface;

final class RequestFreezeMiddleware
{
    public function __invoke(RequestContextInterface $context, callable $next): void
    {
        if (!$context->getRequest()) {
            throw new \RuntimeException('Request not initialized');
        }

        $context->getRequest()->freeze();

        $next($context);
    }
}
