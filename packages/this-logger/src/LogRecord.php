<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Logger;

final readonly class LogRecord
{
    /**
     * @param string $level
     * @param string $message
     * @param array $context
     * @param \DateTimeImmutable $time
     * @param string $channel
     * @param array<array-key, non-empty-string> $categories
     */
    public function __construct(
        public string $level,
        public string $message,
        public array $context,
        public \DateTimeImmutable $time,
        public string $channel,
        public array $categories = [],
    ) {
    }
}
