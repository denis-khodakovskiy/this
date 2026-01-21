<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

use App\Factories\LoggerFactory;
use App\Factories\MessagesHandlersRegistryFactory;
use App\Factories\RequestResolversRegistryFactory;
use App\Factories\RouterPolicyRegistryFactory;
use App\This\Core\Error\ExceptionHandler;
use App\This\Core\Kernel\KernelConfigProvider;
use App\This\Core\Request\RequestProvider;
use App\This\Core\Routing\RouteRegistry;
use Psr\Log\LoggerInterface;
use This\Contracts\ContainerInterface;
use This\Contracts\ExceptionHandlerInterface;
use This\Contracts\KernelConfigProviderInterface;
use This\Contracts\RequestProviderInterface;
use This\Contracts\RequestResolversRegistryInterface;
use This\Contracts\RouterPolicyRegistryInterface;
use This\Messenger\Handler\MessagesHandlersRegistryInterface;
use This\Validator\Validator\Validator;
use This\Validator\Validator\ValidatorInterface;

return function (ContainerInterface $container): void {
    $container
        ->singleton(id: MessagesHandlersRegistryInterface::class, definition: new MessagesHandlersRegistryFactory())
        ->bind(id: RequestResolversRegistryInterface::class, definition: new RequestResolversRegistryFactory())
        ->bind(id: RouterPolicyRegistryInterface::class, definition: new RouterPolicyRegistryFactory())
        ->bind(id: ExceptionHandlerInterface::class, definition: static fn () => new ExceptionHandler())
        ->bind(id: ValidatorInterface::class, definition: static fn () => new Validator())
        ->singleton(id: RouteRegistry::class, definition: static fn () => new RouteRegistry())
        ->singleton(id: RequestProviderInterface::class, definition: static fn () => new RequestProvider())
        ->singleton(
            id: LoggerInterface::class,
            definition: static fn (ContainerInterface $container) => (new LoggerFactory(
                $container->get(id: KernelConfigProviderInterface::class),
            ))(),
        )
        ->singleton(id: KernelConfigProviderInterface::class, definition: static fn () => new KernelConfigProvider())
    ;
};
