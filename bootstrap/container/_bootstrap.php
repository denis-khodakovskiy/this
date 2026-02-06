<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

use App\This\Core\Kernel\KernelConfig;
use App\This\Infrastructure\Container\Container;
use This\Contracts\ContainerInterface;

$files = [
    require_once __DIR__ . '/handlers.php',
    require_once __DIR__ . '/middlewares.php',
    require_once __DIR__ . '/services.php',
    require_once __DIR__ . '/messenger.php',
    require_once __DIR__ . '/cache.php',
    require_once __DIR__ . '/repository.php',
    require_once __DIR__ . '/../../vendor/this/i18n/bootstrap/i18n.container.php',
    require_once __DIR__ . '/../../vendor/this/orm/bootstrap/orm.container.php',
    require_once __DIR__ . '/../../vendor/this/orm/bootstrap/migration-engine.container.php',
];

return function (KernelConfig $kernelConfig) use ($files): ContainerInterface {
    $container = new Container();

    foreach ($files as $callback) {
        $callback($container, $kernelConfig);
    }

    return $container;
};
