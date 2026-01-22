<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App\This\Middlewares\Response;

use http\Header;
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

        http_response_code($response->statusCode);

        echo $response->content;
    }
}
