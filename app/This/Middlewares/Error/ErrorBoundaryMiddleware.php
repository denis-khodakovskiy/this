<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App\This\Middlewares\Error;

use This\Contracts\ContextInterface;
use This\Contracts\ExceptionHandlerInterface;
use This\Contracts\MiddlewareInterface;

final readonly class ErrorBoundaryMiddleware implements MiddlewareInterface
{
    public function __construct(
        private ExceptionHandlerInterface $exceptionHandler,
    ) {
    }

    public function __invoke(ContextInterface $context, callable $next): void
    {
        try {
            $next($context);
        } catch (\Throwable $exception) {
            $context->setException($exception);

            $this->exceptionHandler->handle($exception, $context);
        }
    }
}
