<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Messenger\Bus;

use This\Messenger\Channel\ChannelEnum;
use This\Messenger\Envelop\Envelope;

final readonly class QueryBus implements BusInterface
{
    public function __construct(
        private ExecutionBusInterface $executionBus,
    ) {
    }

    public function handle(object $message): mixed
    {
        $envelop = new Envelope(message: $message, channel: ChannelEnum::QUERY);

        return $this->executionBus->execute(envelope: $envelop);
    }
}
