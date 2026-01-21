<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Messenger\Handler;

interface MessagesHandlersRegistryInterface
{
    public function addHandler(string $messageFQCN, string $handlerFQCN): self;

    public function get(string $messageFQCN): mixed;
}
