<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

use App\This\Middlewares\Context\ContextInitMiddleware;
use App\This\Middlewares\Error\ErrorBoundaryMiddleware;
use App\This\Middlewares\Execution\ExecutionMiddleware;
use App\This\Middlewares\Request\RequestFreezeMiddleware;
use App\This\Middlewares\Request\RequestInitMiddleware;
use App\This\Middlewares\Response\ResponseHandlingMiddleware;
use App\This\Middlewares\Routing\RouterMiddleware;
use This\Controller\Middleware\ControllerResolverMiddleware;
use This\Validator\Middleware\ValidationMiddleware;

return [
    ErrorBoundaryMiddleware::class,
    ContextInitMiddleware::class,
    RequestInitMiddleware::class,
    RouterMiddleware::class,
    RequestFreezeMiddleware::class,
    ValidationMiddleware::class,
    ControllerResolverMiddleware::class,
    ExecutionMiddleware::class,
    ResponseHandlingMiddleware::class
];
