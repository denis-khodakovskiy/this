<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App\This\Core\Routing;

use This\Contracts\RouteInterface;

final class RouteRegistry
{
    private array $routes = [];

    private bool $freeze = false;

    public function addRoute(RouteInterface $route): self
    {
        $this->routes[] = $route;

        return $this;
    }

    /**
     * @return array<RouteInterface>
     */
    public function getRoutes(): array
    {
        return $this->routes;
    }

    public function freeze(): void
    {
        $this->freeze = true;
    }

    private function checkFreeze(): void
    {
        if ($this->freeze) {
            throw new \RuntimeException(message: 'Router is freeze and can not be modified.');
        }
    }
}
