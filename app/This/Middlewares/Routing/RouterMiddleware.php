<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App\This\Middlewares\Routing;

use App\This\Core\Routing\RouteRegistry;
use This\Contracts\ContextInterface;
use This\Contracts\MiddlewareInterface;
use This\Contracts\RequestInterface;
use This\Contracts\RouteInterface;
use This\Contracts\RouterPolicyRegistryInterface;

final readonly class RouterMiddleware implements MiddlewareInterface
{
    private const string DEFAULT_TOKEN_REGEX = '[^\/]+';

    public function __construct(
        private RouteRegistry $routeRegistry,
        private RouterPolicyRegistryInterface $routerPolicyRegistry,
    ) {
    }

    public function __invoke(ContextInterface $context, callable $next): void
    {
        if (!$context->getRequest()) {
            throw new \RuntimeException('Request not initialized');
        }

        $routes = array_filter(
            $this->routeRegistry->getRoutes(),
            static function (RouteInterface $route) use ($context) {
                return match (true) {
                    $context->isCli() => $route->isCli(),
                    $context->isHttp() => $route->isHttp(),
                };
            }
        );

        $directRoutes = $tokenizedRoutes = [];

        foreach ($routes as $route) {
            if (!str_contains($route->getPath(), '{')) {
                $directRoutes[] = $route;
            } else {
                $tokenizedRoutes[] = $route;
            }
        }

        $routes = [
            ...$this->findByDirectPath($context->getRequest(), $directRoutes),
            ...$this->findByTokenizedPath($context->getRequest(), $tokenizedRoutes),
        ];

        if (!$context->isCli()) {
            $routes = array_filter(
                $routes,
                static function (RouteInterface $route) use ($context) {
                    return in_array($context->getRequest()->getMethod(), $route->getMethods());
                }
            );
        }

        $routes = array_filter(
            $routes,
            function (RouteInterface $route) use ($context): bool {
                foreach ($route->getPolicies() as $policy) {
                    $resolver = $this->routerPolicyRegistry->getResolver($policy);

                    if (!$resolver->resolve($policy, $context->getRequest())) {
                        return false;
                    }
                }

                return true;
            },
        );

        if (empty($routes)) {
            throw new \RuntimeException('No routes found');
        }

        $route = array_shift($routes);

        if (str_contains($route->getPath(), '{')) {
            $regex = $this->getPathRegex($route->getPath(), $route->getRequirements());
            preg_match($regex, $context->getRequest()->getPath(), $matches);

            foreach ($matches as $index => $match) {
                if (is_int($index)) {
                    unset($matches[$index]);
                }
            }

            $context->getRequest()->setPathParameters($matches);
        }

        $context->setRoute($route);

        $next($context);
    }

    /**
     * @param array<RouteInterface> $routes
     * @return array<RouteInterface>
     */
    public function findByDirectPath(RequestInterface $request, array $routes): array
    {
        return array_filter(
            $routes,
            static function (RouteInterface $route) use ($request) {
                return $route->getPath() === $request->getPath();
            },
        );
    }

    /**
     * @param array<RouteInterface> $routes
     * @return array<RouteInterface>
     */
    public function findByTokenizedPath(RequestInterface $request, array $routes): array
    {
        return array_filter(
            $routes,
            function (RouteInterface $route) use ($request) {
                $pathRegex = $this->getPathRegex($route->getPath(), $route->getRequirements());

                return preg_match($pathRegex, $request->getPath()) === 1;
            },
        );
    }

    /**
     * @param array<non-empty-string, non-empty-string> $requirements
     */
    private function getPathRegex(string $path, array $requirements = []): string
    {
        $segments = explode('/', trim($path, '/'));
        $patternParts = [];

        foreach ($segments as $segment) {
            // token: {id}
            if (preg_match('/^\{([a-zA-Z_][a-zA-Z0-9_]*)\}$/', $segment, $m)) {
                $name = $m[1];
                $regex = $requirements[$name] ?? self::DEFAULT_TOKEN_REGEX;

                $patternParts[] = sprintf(
                    '(?P<%s>%s)',
                    $name,
                    $regex
                );
                continue;
            }

            // static segment
            $patternParts[] = preg_quote($segment, '#');
        }

        return '#^/' . implode('/', $patternParts) . '$#';
    }
}
