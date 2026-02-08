<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App;

use App\This\Core\Kernel\KernelConfig;
use App\This\Core\Request\RequestContext;
use App\This\Core\Routing\RouteRegistry;
use Psr\Container\ContainerExceptionInterface;
use Psr\Container\NotFoundExceptionInterface;
use This\Contracts\ContainerInterface;
use This\Contracts\KernelConfigProviderInterface;
use This\Contracts\KernelInterface;
use This\Contracts\RequestContextInterface;
use This\Contracts\RequestMetaCollectorInterface;

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

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function getContext(): RequestContext
    {
        return new RequestContext(
            container: $this->container,
            meta: $this->container->get(id: RequestMetaCollectorInterface::class),
        );
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    private function launchPipeline(): void
    {
        $container = $this->container;

        $next = function (RequestContextInterface $context): void {};

        foreach (array_reverse($this->middlewares) as $id) {
            $middleware = $container->get($id);

            $prevNext = $next;

            $next = function (RequestContextInterface $context) use ($middleware, $prevNext): void {
                $middleware($context, $prevNext);
            };
        }

        $context = $this->getContext();
        $next($context);
    }
}
