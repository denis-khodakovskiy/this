<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

use App\This\Core\Routing\Route;
use App\This\Core\Routing\RouteRegistry;
use This\Contracts\RouteEnvEnum;
use This\ORM\Migrations\Handler\Migrator;

return static function (RouteRegistry $router): void {
    $router
        ->addRoute(new Route(
            name: 'app.migrator',
            path: '/migration/{action}',
            handler: Migrator::class,
            env: RouteEnvEnum::CLI,
        ))
    ;
};
