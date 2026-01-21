<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Messenger\Messenger;

use This\Messenger\Bus\CommandBus;
use This\Messenger\Bus\EventBus;
use This\Messenger\Bus\QueryBus;

final readonly class Messenger implements MessengerInterface
{
    public function __construct(
        private CommandBus $commandBus,
        private EventBus $eventBus,
        private QueryBus $queryBus,
    ) {
    }

    public function dispatch(object $message): void
    {
        $this->commandBus->handle($message);
    }

    public function ask(object $message): mixed
    {
        return $this->queryBus->handle($message);
    }

    public function emmit(object $message): void
    {
        $this->eventBus->handle($message);
    }
}
