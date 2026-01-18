<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

use App\Factories\LoggerFactory;
use App\Factories\RequestResolversRegistryFactory;
use App\Factories\RouterPolicyRegistryFactory;
use App\Handlers\HelloWorldHandler;
use App\Handlers\User\CreateUserHandler;
use App\Services\MyService;
use App\Services\MyServiceInterface;
use App\This\Core\Error\ExceptionHandler;
use App\This\Core\Kernel\KernelConfig;
use App\This\Core\Request\RequestProvider;
use App\This\Core\Routing\RouteRegistry;
use App\This\Infrastructure\Container\Container;
use App\This\Middlewares\Context\ContextInitMiddleware;
use App\This\Middlewares\Error\ErrorBoundaryMiddleware;
use App\This\Middlewares\Execution\ExecutionMiddleware;
use App\This\Middlewares\Request\RequestFreezeMiddleware;
use App\This\Middlewares\Request\RequestInitMiddleware;
use App\This\Middlewares\Routing\RouterMiddleware;
use Psr\Log\LoggerInterface;
use This\Contracts\ContainerInterface;
use This\Contracts\ExceptionHandlerInterface;
use This\Contracts\RequestProviderInterface;
use This\Contracts\RequestResolversRegistryInterface;
use This\Contracts\RouterPolicyRegistryInterface;
use This\Validator\Middleware\ValidationMiddleware;

return function (KernelConfig $kernelConfig): ContainerInterface {
    $container = new Container();

    $container
        //Middlewares
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

        //Services
        ->bind(id: RequestResolversRegistryInterface::class, definition: new RequestResolversRegistryFactory())
        ->bind(id: RouterPolicyRegistryInterface::class, definition: new RouterPolicyRegistryFactory())
        ->bind(id: ExceptionHandlerInterface::class, definition: static fn () => new ExceptionHandler())
        ->bind(id: MyServiceInterface::class, definition: static fn () => new MyService())
        ->singleton(id: RouteRegistry::class, definition: static fn () => new RouteRegistry())
        ->singleton(id: RequestProviderInterface::class, definition: static fn () => new RequestProvider())
        ->singleton(id: LoggerInterface::class, definition: new LoggerFactory($kernelConfig))

        //Handlers
        ->bind(id: HelloWorldHandler::class, definition: static fn (ContainerInterface $container) => new HelloWorldHandler())
        ->bind(id: CreateUserHandler::class, definition: static fn (ContainerInterface $container) => new CreateUserHandler(
            $container->get(id: MyServiceInterface::class),
        ))
    ;

    return $container;
};
