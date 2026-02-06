<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

use App\Handlers\CacheTestHandler;
use App\Handlers\MessengerTestHandler;
use App\Handlers\TestHandler;
use App\This\Core\Routing\Route;
use App\This\Core\Routing\RouteRegistry;
use This\Contracts\RouteEnvEnum;

return function (RouteRegistry $router) {
    $router
        ->addRoute(new Route(name: 'index.http', path: '/', handler: \App\Handlers\Http\IndexHandler::class))
        ->addRoute(new Route(
            name: 'index.cli',
            path: '/', handler:
            \App\Handlers\Cli\IndexHandler::class,
            env: RouteEnvEnum::CLI,
        ))
        ->addRoute(new Route(
            name: 'cache.test',
            path: '/cache',
            handler: CacheTestHandler::class,
        ))
        ->addRoute(new Route(
            name: 'test',
            path: '/test',
            handler: TestHandler::class,
        ))
        ->addRoute(new Route(
            name: 'messenger.test',
            path: '/messenger',
            handler: MessengerTestHandler::class,
        ))
        ->addRoute(new Route(
            name: 'controller.test',
            path: '/user/list',
            handler: \App\Handlers\UserController::class,
        ))
        ->addRoute(new Route(
            name: 'orm.test',
            path: '/orm',
            handler: \App\Handlers\ORMHandler::class,
        ))
        ->addRoute(new Route(
            name: 'orm.test',
            path: '/orm/{action}/{id}',
            handler: \App\Handlers\ORMHandler::class,
            requirements: ['action' => '[a-z]+'],
        ))
    ;
};
