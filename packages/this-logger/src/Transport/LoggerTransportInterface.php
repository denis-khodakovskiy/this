<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Logger\Transport;

use This\Logger\LogRecord;

interface LoggerTransportInterface
{
    public function supports(string $level): bool;

    public function write(LogRecord $record): void;
}
