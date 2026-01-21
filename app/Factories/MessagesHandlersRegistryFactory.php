<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App\Factories;

use This\Messenger\Handler\MessagesHandlersRegistry;
use This\Messenger\Handler\MessagesHandlersRegistryInterface;

final class MessagesHandlersRegistryFactory
{
    public function __invoke(): MessagesHandlersRegistryInterface
    {
        return (new MessagesHandlersRegistry)
            ->addHandler(messageFQCN: MyMessage1::class, handlerFQCN: MyHandler1::class)
            ->addHandler(messageFQCN: MyMessage2::class, handlerFQCN: MyHandler2::class)
            ->addHandler(messageFQCN: MyMessage3::class, handlerFQCN: MyHandler3::class)
        ;
    }
}
