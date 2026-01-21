<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Messenger\Bus;

use This\Messenger\Envelop\Envelope;

interface ExecutionBusInterface
{
    public function execute(Envelope $envelope): mixed;
}
