<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Logger;

use This\Logger\Transport\LoggerTransportInterface;

final readonly class LoggerChannel
{
    /**
     * @param array<array-key, non-empty-string> $categories
     */
    public function __construct(
        private LoggerTransportInterface $transport,
        private string $minLevel,
        private array $categories = [],
    ) {
    }

    public function accepts(LogRecord $record): bool
    {
        if (!LogLevelHelper::allows(min: $this->minLevel, current: $record->level)) {
            return false;
        }

        if (empty($this->categories)) {
            return true;
        }

        foreach ($record->categories as $category) {
            if (in_array($category, $this->categories, true)) {
                return true;
            }
        }

        return false;
    }

    public function handle(LogRecord $record): void
    {
        $this->transport->write(record: $record);
    }
}
