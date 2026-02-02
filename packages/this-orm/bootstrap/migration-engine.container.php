<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

use This\Contracts\ContainerInterface;
use This\Contracts\EnvContainerInterface;
use This\Contracts\RequestProviderInterface;
use This\ORM\Migrations\Handler\Migrator;

return static function (ContainerInterface $container): void {
    $container->bind(id: Migrator::class, definition: static fn (ContainerInterface $container) => new Migrator(
        $container->get(id: EnvContainerInterface::class)->get('MIGRATIONS_PATH'),
        $container->get(id: RequestProviderInterface::class)->getRequest(),
    ));
};
