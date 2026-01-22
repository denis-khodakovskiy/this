<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

use App\Kernel;
use App\This\Core\Kernel\KernelConfig;

require_once "vendor/autoload.php";
$container = require_once "app/bootstrap/container/_bootstrap.php";
$router = require_once "app/bootstrap/router/_bootstrap.php";
$middlewares = require_once "app/bootstrap/middlewares.php";

(new Kernel(
    $container,
    $router,
    $middlewares,
    new KernelConfig(
        appDir: __DIR__ . '/app',
        varDir: __DIR__ . '/app/var',
    )
))->run();
