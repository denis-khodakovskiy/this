<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Logger\Transport;

use This\Logger\LogRecord;

final readonly class FileTransport implements LoggerTransportInterface
{
    public function __construct(
        private string $filePath,
    ) {
    }

    public function supports(string $level): bool
    {
        return true;
    }

    public function write(LogRecord $record): void
    {
        if (!file_put_contents(
            $this->filePath,
            sprintf(
                "[%s] %s: %s %s\n",
                $record->time->format('c'),
                strtoupper($record->level),
                $record->message,
                json_encode($record->context)
            ),
            FILE_APPEND
        )) {
            throw new \RuntimeException("Unable to write to file {$this->filePath}.");
        }
    }
}
