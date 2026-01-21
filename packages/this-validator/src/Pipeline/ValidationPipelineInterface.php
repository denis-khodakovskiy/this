<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Validator\Pipeline;

interface ValidationPipelineInterface
{
    /**
     * @param array<string> $middlewares
     */
    public function addPreValidationMiddlewares(array $middlewares): void;

    /**
     * @param array<string> $middlewares
     */
    public function addPostValidationMiddlewares(array $middlewares): void;

    public function run(callable $next): void;
}
