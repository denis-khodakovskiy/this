<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

use App\Handlers\CLI\CLIIndexHandler;
use App\Handlers\HTTP\HTTPIndexHandler;
use App\This\Core\Routing\Route;
use App\This\Core\Routing\RouteRegistry;
use This\Contracts\RouteEnvEnum;

return function (RouteRegistry $router) {
    $router
        ->addRoute(new Route(name: 'index.http', path: '/', handler: HTTPIndexHandler::class))
        ->addRoute(new Route(name: 'index.cli', path: '/', handler: CLIIndexHandler::class, env: RouteEnvEnum::CLI))
    ;
};
