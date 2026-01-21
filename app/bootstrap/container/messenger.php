<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

use App\DDDContext\Application\Command\MyCommand;
use App\DDDContext\Application\CommandHandler\MyCommandHandler;
use App\DDDContext\Application\Query\MyQuery;
use App\DDDContext\Application\QueryHandler\MyQueryHandler;
use App\Handlers\MessengerTestHandler;
use This\Contracts\ContainerInterface;
use This\Messenger\Bus\CommandBus;
use This\Messenger\Bus\EventBus;
use This\Messenger\Bus\ExecutionBus;
use This\Messenger\Bus\QueryBus;
use This\Messenger\Handler\HandlerInvoker;
use This\Messenger\Handler\MessagesHandlersRegistry;
use This\Messenger\Handler\MessagesHandlersRegistryInterface;
use This\Messenger\Messenger\Messenger;
use This\Messenger\Messenger\MessengerInterface;
use This\Messenger\Middleware\MessengerExecutionMiddleware;
use This\Messenger\Middleware\MiddlewarePipeline;

return static function (ContainerInterface $container) {
    $container
        ->bind(
            id: MessagesHandlersRegistryInterface::class,
            definition: static fn () => (new MessagesHandlersRegistry())
                ->addHandler(messageFQCN: MyCommand::class, handlerFQCN: MyCommandHandler::class)
                ->addHandler(messageFQCN: MyQuery::class, handlerFQCN: MyQueryHandler::class)
        )

        ->bind(id: MyCommandHandler::class, definition: static fn () => new MyCommandHandler())
        ->bind(id: MyQueryHandler::class, definition: static fn () => new MyQueryHandler())
        ->bind(id: MessengerTestHandler::class, definition: static fn (ContainerInterface $container) => new MessengerTestHandler(
            $container->get(id: MessengerInterface::class),
        ))

        ->singleton(
            id: MessengerInterface::class,
            definition: static function (ContainerInterface $container) {
                $executionBus = new ExecutionBus(
                    pipeline: new MiddlewarePipeline(middlewares: [new MessengerExecutionMiddleware()]),
                    invoker: new HandlerInvoker(
                        registry: $container->get(MessagesHandlersRegistryInterface::class),
                        container: $container,
                    ),
                );

                return new Messenger(
                    commandBus: new CommandBus(executionBus: $executionBus),
                    eventBus: new EventBus(executionBus: $executionBus),
                    queryBus: new QueryBus(executionBus: $executionBus),
                );
            },
        )
    ;
};
