<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Messenger\Envelop;

use This\Messenger\Channel\ChannelEnum;

final readonly class Envelope
{
    public function __construct(
        public object $message,
        public ChannelEnum $channel,
        public array $stamps = [],
    ) {
    }
}
