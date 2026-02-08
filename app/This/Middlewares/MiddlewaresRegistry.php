<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App\This\Middlewares;

use App\This\Middlewares\Context\ContextInitMiddleware;
use App\This\Middlewares\Error\ErrorBoundaryMiddleware;
use App\This\Middlewares\Execution\ExecutionMiddleware;
use App\This\Middlewares\Request\RequestFreezeMiddleware;
use App\This\Middlewares\Request\RequestInitMiddleware;
use App\This\Middlewares\Response\ResponseHandlingMiddleware;
use App\This\Middlewares\Routing\RouterMiddleware;
use This\Security\Middleware\SecurityMiddleware;
use This\Session\Middleware\SessionMiddleware;

final class MiddlewaresRegistry
{
    public static function getMiddlewares(): array
    {
        return [
            ErrorBoundaryMiddleware::class,
            ContextInitMiddleware::class,
            SessionMiddleware::class,
            RequestInitMiddleware::class,
            RouterMiddleware::class,
            RequestFreezeMiddleware::class,
            SecurityMiddleware::class,
            ExecutionMiddleware::class,
            ResponseHandlingMiddleware::class
        ];
    }
}
