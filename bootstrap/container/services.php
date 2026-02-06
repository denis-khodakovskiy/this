<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

use App\This\Core\Env\EnvContainer;
use App\This\Core\Error\ExceptionHandler;
use App\This\Core\Kernel\KernelConfigProvider;
use App\This\Core\Request\RequestProvider;
use App\This\Core\Routing\RouteRegistry;
use App\This\Infrastructure\Factories\RequestResolversRegistryFactory;
use App\This\Infrastructure\Factories\RouterPolicyRegistryFactory;
use This\Contracts\ContainerInterface;
use This\Contracts\EnvContainerInterface;
use This\Contracts\ExceptionHandlerInterface;
use This\Contracts\KernelConfigProviderInterface;
use This\Contracts\RequestProviderInterface;
use This\Contracts\RequestResolversRegistryInterface;
use This\Contracts\RouterPolicyRegistryInterface;

return function (ContainerInterface $container): void {
    $container
        ->bind(id: RequestResolversRegistryInterface::class, definition: new RequestResolversRegistryFactory())
        ->bind(id: RouterPolicyRegistryInterface::class, definition: new RouterPolicyRegistryFactory())
        ->bind(id: ExceptionHandlerInterface::class, definition: static fn () => new ExceptionHandler())
        ->singleton(id: RouteRegistry::class, definition: static fn () => new RouteRegistry())
        ->singleton(id: RequestProviderInterface::class, definition: static fn () => new RequestProvider())
        ->singleton(id: KernelConfigProviderInterface::class, definition: static fn () => new KernelConfigProvider())
        ->singleton(id: EnvContainerInterface::class, definition: static fn (ContainerInterface $container) => new EnvContainer(
            $container->get(KernelConfigProviderInterface::class)->getConfig()->path('%root%') . '/.env',
        ))
    ;
};
