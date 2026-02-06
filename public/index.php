<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

use App\Kernel;
use App\This\Core\Kernel\KernelConfig;
use App\This\Middlewares\MiddlewaresRegistry;

require_once "../vendor/autoload.php";

$container = require_once "../bootstrap/container/_bootstrap.php";
$router = require_once "../bootstrap/router/_bootstrap.php";

(new Kernel(
    $container,
    $router,
    MiddlewaresRegistry::getMiddlewares(),
    new KernelConfig(
        rootDir: __DIR__ . '/..',
        appDir: __DIR__ . '/../app',
        varDir: __DIR__ . '/../app/var',
    )
))->run();
