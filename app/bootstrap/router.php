<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

use App\Forms\CreateUserFormSchema;
use App\Handlers\Dto\CreateUserRequestDto;
use App\Handlers\HelloWorldHandler;
use App\Handlers\IndexHandler;
use App\Handlers\User\CreateUserHandler;
use App\This\Core\Routing\Route;
use App\This\Core\Routing\RouteRegistry;
use This\Contracts\RouteEnvEnum;

return function (RouteRegistry $router) {
    $router
        ->addRoute(new Route(name: 'index', path: '/', handler: IndexHandler::class))
        ->addRoute(new Route(
            name: 'hello.world',
            path: '/hello-world',
            handler: HelloWorldHandler::class,
        ))
        ->addRoute(new Route(
            name: 'hello.world.cli',
            path: '/hello-world/cli',
            handler: HelloWorldHandler::class,
            env: RouteEnvEnum::CLI,
        ))
        ->addRoute(new Route(
            name: 'user.create',
            path: '/users',
            handler: CreateUserHandler::class,
            requestFQCN: CreateUserRequestDto::class,
            meta: [
                CreateUserFormSchema::class,
            ],
        ))
    ;
};
