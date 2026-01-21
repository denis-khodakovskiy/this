<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Messenger\Messenger;

interface MessengerInterface
{
    public function dispatch(object $message): void;

    public function ask(object $message): mixed;

    public function emmit(object $message): void;
}
