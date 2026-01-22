<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App\This\Middlewares\Response;

use This\Contracts\ContextInterface;
use This\Contracts\MiddlewareInterface;

final class ResponseHandlingMiddleware implements MiddlewareInterface
{
    public function __invoke(ContextInterface $context, callable $next): void
    {
        if (!$context->getResponse()) {
            throw new \RuntimeException('Response not set');
        }

        $response = $context->getResponse();

        if (null !== $response->content) {
            echo $response->content;
        }
    }
}
