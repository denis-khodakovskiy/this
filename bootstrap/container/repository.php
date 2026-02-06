<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

use App\Repository\UserRepository;
use App\Repository\UserRepositoryInterface;
use App\Schemas\UserSchema;
use This\Contracts\ContainerInterface;
use This\ORM\Hydrator\HydratorInterface;
use This\ORM\Repository\RepositoryContextInterface;

return function (ContainerInterface $container): void {
    $container
        ->bind(
            id: UserRepositoryInterface::class,
            definition: static fn (ContainerInterface $container) => new UserRepository(
                $container->get(id: RepositoryContextInterface::class)->for(UserSchema::class),
                $container->get(id: HydratorInterface::class),
            ),
        )
    ;
};
