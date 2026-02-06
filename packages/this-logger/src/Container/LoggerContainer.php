<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Logger\Container;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use This\Contracts\ContainerInterface;
use This\Logger\Logger;
use This\Logger\LoggerChannel;
use This\Logger\Transport\LoggerTransportInterface;

final class LoggerContainer
{
    public static function register(): \Closure
    {
        return static function (ContainerInterface $container) {
            $container
                ->singleton(
                    id: LoggerInterface::class,
                    definition: static fn () => new Logger(
                        channels: [
                            new LoggerChannel(
                                $container->get(id: LoggerTransportInterface::class),
                                minLevel: LogLevel::DEBUG,
                            ),
                        ],
                    ),
                )
            ;
        };
    }
}
