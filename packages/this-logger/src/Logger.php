<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Logger;

use Psr\Log\LoggerInterface;
use Psr\Log\LogLevel;

final readonly class Logger implements LoggerInterface
{
    /**
     * @param array<array-key, LoggerChannel> $channels
     */
    public function __construct(
        private array $channels,
    ) {
    }

    public function emergency(\Stringable|string $message, array $context = [], array $categories = []): void
    {
        $this->log(LogLevel::EMERGENCY, $message, $context, $categories);
    }

    public function alert(\Stringable|string $message, array $context = [], array $categories = []): void
    {
        $this->log(LogLevel::ALERT, $message, $context, $categories);
    }

    public function critical(\Stringable|string $message, array $context = [], array $categories = []): void
    {
        $this->log(LogLevel::CRITICAL, $message, $context, $categories);
    }

    public function error(\Stringable|string $message, array $context = [], array $categories = []): void
    {
        $this->log(LogLevel::ERROR, $message, $context, $categories);
    }

    public function warning(\Stringable|string $message, array $context = [], array $categories = []): void
    {
        $this->log(LogLevel::WARNING, $message, $context, $categories);
    }

    public function notice(\Stringable|string $message, array $context = [], array $categories = []): void
    {
        $this->log(LogLevel::NOTICE, $message, $context, $categories);
    }

    public function info(\Stringable|string $message, array $context = [], array $categories = []): void
    {
        $this->log(LogLevel::INFO, $message, $context, $categories);
    }

    public function debug(\Stringable|string $message, array $context = [], array $categories = []): void
    {
        $this->log(LogLevel::DEBUG, $message, $context, $categories);
    }

    public function log($level, \Stringable|string $message, array $context = [], array $categories = []): void
    {
        $record = new LogRecord(
            level: $level,
            message: $message,
            context: $context,
            time: new \DateTimeImmutable(),
            channel: $context['channel'] ?? 'app',
            categories: $categories,
        );

        foreach ($this->channels as $channel) {
            if ($channel->accepts($record)) {
                $channel->handle($record);
            }
        }
    }
}
