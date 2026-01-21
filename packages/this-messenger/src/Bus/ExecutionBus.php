<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Messenger\Bus;

use This\Messenger\Envelop\Envelope;
use This\Messenger\Handler\HandlerInvokerInterface;
use This\Messenger\Middleware\MiddlewarePipelineInterface;

final readonly class ExecutionBus implements ExecutionBusInterface
{
    public function __construct(
        private MiddlewarePipelineInterface $pipeline,
        private HandlerInvokerInterface $invoker,
    ) {
    }

    public function execute(Envelope $envelope): mixed
    {
        return $this->pipeline->handle(
            $envelope,
            fn (Envelope $envelope) => $this->invoker->invoke($envelope)
        );
    }
}
