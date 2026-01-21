<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Messenger\Middleware;

use This\Messenger\Envelop\Envelope;

interface MessengerMiddlewareInterface
{
    public function handle(Envelope $envelope, callable $next): mixed;
}
