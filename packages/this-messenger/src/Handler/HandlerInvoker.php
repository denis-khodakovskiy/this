<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Messenger\Handler;

use Psr\Container\ContainerExceptionInterface;
use Psr\Container\ContainerInterface;
use Psr\Container\NotFoundExceptionInterface;
use This\Messenger\Envelop\Envelope;

final readonly class HandlerInvoker implements HandlerInvokerInterface
{
    public function __construct(
        private MessagesHandlersRegistryInterface $registry,
        private ContainerInterface $container,
    ) {
    }

    /**
     * @throws ContainerExceptionInterface
     * @throws NotFoundExceptionInterface
     */
    public function invoke(Envelope $envelope): mixed
    {
        $message = $envelope->message;
        $messageClass = $message::class;
        $handlerClass = $this->registry->get($messageClass);
        $handler = $this->container->get($handlerClass);

        return $handler($message);
    }
}
