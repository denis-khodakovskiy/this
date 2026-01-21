<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Messenger\Middleware;

use This\Messenger\Envelop\Envelope;

final readonly class MiddlewarePipeline implements MiddlewarePipelineInterface
{
    /**
     * @param array<MessengerMiddlewareInterface> $middlewares
     */
    public function __construct(
        private array $middlewares = []
    ) {
    }

    public function handle(
        Envelope $envelope,
        callable $final
    ): mixed {
        $next = $final;

        foreach (array_reverse($this->middlewares) as $middleware) {
            $next = static function (Envelope $env) use ($middleware, $next) {
                return $middleware->handle($env, $next);
            };
        }

        return $next($envelope);
    }
}
