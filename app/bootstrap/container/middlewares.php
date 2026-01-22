<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

use App\This\Core\Routing\RouteRegistry;
use App\This\Middlewares\Context\ContextInitMiddleware;
use App\This\Middlewares\Error\ErrorBoundaryMiddleware;
use App\This\Middlewares\Execution\ExecutionMiddleware;
use App\This\Middlewares\Request\RequestFreezeMiddleware;
use App\This\Middlewares\Request\RequestInitMiddleware;
use App\This\Middlewares\Response\ResponseHandlingMiddleware;
use App\This\Middlewares\Routing\RouterMiddleware;
use This\Contracts\ContainerInterface;
use This\Contracts\ExceptionHandlerInterface;
use This\Contracts\RouterPolicyRegistryInterface;
use This\Validator\Middleware\ValidationMiddleware;

return function (ContainerInterface $container): void {
    $container
        ->bind(id: ErrorBoundaryMiddleware::class, definition: static fn () => new ErrorBoundaryMiddleware(
            $container->get(id: ExceptionHandlerInterface::class)
        ))
        ->bind(id: ContextInitMiddleware::class, definition: static fn () => new ContextInitMiddleware())
        ->bind(id: RequestInitMiddleware::class, definition: static fn () => new RequestInitMiddleware())
        ->bind(id: RouterMiddleware::class, definition: static fn () => new RouterMiddleware(
            $container->get(id: RouteRegistry::class),
            $container->get(id: RouterPolicyRegistryInterface::class),
        ))
        ->bind(id: RequestFreezeMiddleware::class, definition: static fn () => new RequestFreezeMiddleware())
        ->bind(id: ValidationMiddleware::class, definition: static fn () => new ValidationMiddleware())
        ->bind(id: ExecutionMiddleware::class, definition: static fn () => new ExecutionMiddleware())
        ->bind(id: ResponseHandlingMiddleware::class, definition: static fn () => new ResponseHandlingMiddleware())
    ;
};
