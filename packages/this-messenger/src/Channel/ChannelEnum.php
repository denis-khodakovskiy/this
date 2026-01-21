<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Messenger\Channel;

enum ChannelEnum: string
{
    case QUERY = 'query';
    case EVENT = 'event';
    case COMMAND = 'command';
}
