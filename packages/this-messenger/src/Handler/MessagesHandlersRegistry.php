<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Messenger\Handler;

use This\Messenger\Exception\HandlerNotFoundException;

class MessagesHandlersRegistry implements MessagesHandlersRegistryInterface
{
    private array $handlers = [];

    public function addHandler(string $messageFQCN, string $handlerFQCN): self
    {
        $this->handlers[$messageFQCN] = $handlerFQCN;

        return $this;
    }

    public function get(string $messageFQCN): mixed
    {
        return $this->handlers[$messageFQCN]
            ?? throw new HandlerNotFoundException(message: "Handler for {$messageFQCN} not found");
    }
}
