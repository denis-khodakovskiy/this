<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App;

use App\This\Core\Kernel\Context;
use App\This\Core\Kernel\KernelConfig;
use App\This\Core\Routing\RouteRegistry;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use This\Contracts\ContainerInterface;
use This\Contracts\ContextInterface;
use This\Contracts\KernelConfigProviderInterface;
use This\Contracts\KernelInterface;

final class Kernel implements KernelInterface
{
    private ContainerInterface $container;

    private bool $booted = false;

    /**
     * @param array<non-empty-string, non-empty-string> $middlewares
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function __construct(
        \Closure $container,
        \Closure $router,
        private readonly array $middlewares,
        KernelConfig $config,
    ) {
        $this->container = $container();
        $this->container->get(id: KernelConfigProviderInterface::class)->setConfig($config);
        $routerRegistry = $this->container->get(id: RouteRegistry::class);
        $router($routerRegistry);
        $routerRegistry->freeze();
        $this->container->freeze();
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function run(): void
    {
        if ($this->booted) {
            throw new \RuntimeException('Kernel is already booted.');
        }

        if (!$this->container->isFreeze()) {
            throw new \RuntimeException('Container is not freeze.');
        }

        if (empty($this->middlewares)) {
            throw new \RuntimeException('You must add at least one middleware.');
        }

        $this->booted = true;

        $this->launchPipeline();
    }

    private function getContext(): Context
    {
        return new Context(
            container: $this->container,
        );
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function launchPipeline(): void
    {
        $container = $this->container;

        $next = function (ContextInterface $context): void {};

        foreach (array_reverse($this->middlewares) as $id) {
            $middleware = $container->get($id);

            $prevNext = $next;

            $next = function (ContextInterface $context) use ($middleware, $prevNext): void {
                $middleware($context, $prevNext);
            };
        }

        $context = $this->getContext();
        $next($context);
    }
}
