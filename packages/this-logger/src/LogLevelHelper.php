<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Logger;

use Psr\Log\LogLevel;

final class LogLevelHelper
{
    private const array MAP = [
        LogLevel::DEBUG => 100,
        LogLevel::INFO => 200,
        LogLevel::NOTICE => 250,
        LogLevel::WARNING => 300,
        LogLevel::ERROR => 400,
        LogLevel::CRITICAL => 500,
        LogLevel::ALERT => 550,
        LogLevel::EMERGENCY => 600,
    ];

    public static function allows(string $min, string $current): bool
    {
        return self::MAP[$current] >= self::MAP[$min];
    }
}
