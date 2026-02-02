<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

use App\This\Core\Routing\RouteRegistry;

$files = [
    require_once __DIR__ . '/app.php',
    require_once __DIR__ . '/../../vendor/this/orm/bootstrap/migration-engine.router.php',
];

return function (RouteRegistry $router) use ($files) {
    foreach ($files as $callback) {
        $callback($router);
    }
};
