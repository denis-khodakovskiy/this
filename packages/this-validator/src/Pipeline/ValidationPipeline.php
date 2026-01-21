<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Validator\Pipeline;

use App\This\Middlewares\Execution\ExecutionMiddleware;

class ValidationPipeline implements ValidationPipelineInterface
{
    private array $middlewares = [ExecutionMiddleware::class];

    public function addPreValidationMiddlewares(array $middlewares): void
    {
        $this->middlewares = array_merge($middlewares, $this->middlewares);
    }

    public function addPostValidationMiddlewares(array $middlewares): void
    {
        $this->middlewares = array_merge($this->middlewares, $middlewares);
    }

    public function run(callable $next): void
    {
        foreach (array_reverse($this->middlewares) as $middleware) {
            $prevNext = $next;

            $next = function (ValidationContextInterface $validationContext) use ($middleware, $prevNext): void {
                $middleware($validationContext, $prevNext);
            };
        }
    }
}
