<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Messenger\Handler;

use This\Messenger\Envelop\Envelope;

interface HandlerInvokerInterface
{
    public function invoke(Envelope $envelope): mixed;
}
