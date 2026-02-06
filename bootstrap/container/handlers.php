<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

use App\Handlers\TestHandler;
use Psr\Log\LoggerInterface;
use This\Contracts\ContainerInterface;

return function (ContainerInterface $container): void {
    $container
        ->bind(
            id: \App\Handlers\Http\IndexHandler::class,
            definition: static fn (ContainerInterface $container) => new \App\Handlers\Http\IndexHandler(
                $container->get(id: LoggerInterface::class),
            )
        )
        ->bind(
            id: \App\Handlers\Cli\IndexHandler::class,
            definition: static fn (ContainerInterface $container) => new \App\Handlers\Cli\IndexHandler(
                $container->get(id: LoggerInterface::class),
            )
        )
        ->bind(id: TestHandler::class, definition: static fn (ContainerInterface $container) => new TestHandler(
            $container->get(id: \This\Validator\Validator\ValidatorInterface::class),
        ))
        ->bind(id: \App\Handlers\UserController::class, definition: static fn () => new \App\Handlers\UserController())
        ->bind(id: \App\Handlers\ORMHandler::class, definition: static fn (ContainerInterface $container) => new \App\Handlers\ORMHandler(
            $container->get(id: \App\Repository\UserRepositoryInterface::class),
        ))
        ->bind(
            id: \App\Handlers\RepositoryTestHandler::class,
            definition: static fn (ContainerInterface $container) => new \App\Handlers\RepositoryTestHandler(
                $container->get(\This\ORM\Repository\RepositoryContextInterface::class)->for(\App\Schemas\UserSchema::class)
            ),
        )
    ;
};
