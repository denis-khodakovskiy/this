<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App\Factories;

use App\This\Core\Kernel\KernelConfig;
use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;
use This\Logger\Logger;
use This\Logger\LoggerChannel;
use This\Logger\Transport\FileTransport;

final readonly class LoggerFactory
{
    public function __construct(
        private KernelConfig $config,
    ) {
    }

    public function __invoke(): LoggerInterface
    {
        return new Logger(
            channels: [
                new LoggerChannel(
                    transport: new FileTransport(
                        filePath: $this->config->path(path: '%var%') . '/logs/app.log',
                    ),
                    minLevel: LogLevel::DEBUG,
                ),
            ],
        );
    }
}
