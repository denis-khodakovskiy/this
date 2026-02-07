<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Messenger\Container;

use This\Contracts\ContainerInterface;
use This\Messenger\Bus\CommandBus;
use This\Messenger\Bus\EventBus;
use This\Messenger\Bus\ExecutionBus;
use This\Messenger\Bus\QueryBus;
use This\Messenger\Handler\HandlerInvoker;
use This\Messenger\Handler\MessagesHandlersRegistryInterface;
use This\Messenger\Messenger\Messenger;
use This\Messenger\Messenger\MessengerInterface;
use This\Messenger\Middleware\MessengerExecutionMiddleware;
use This\Messenger\Middleware\MiddlewarePipeline;

final class MessengerContainer
{
    public static function register(): \Closure
    {
        return static function (ContainerInterface $container) {
            $container
//        ->bind(
//            id: MessagesHandlersRegistryInterface ::class,
//            definition: static fn () => (new MessagesHandlersRegistry())
//                ->addHandler(messageFQCN: MyCommand::class, handlerFQCN: MyCommandHandler::class)
//                ->addHandler(messageFQCN: MyQuery::class, handlerFQCN: MyQueryHandler::class)
//        )

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
                    priority: 100,
                )
            ;
        };
    }
}
