<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

use App\This\Infrastructure\Container\Container;
use This\Contracts\ContainerInterface;

$lambdas = [
    require_once __DIR__ . '/handlers.php',
    require_once __DIR__ . '/middlewares.php',
    require_once __DIR__ . '/services.php',
];

return function () use ($lambdas): ContainerInterface {
    $container = new Container();

    foreach ($lambdas as $callback) {
        $callback($container);
    }

    return $container;
};
