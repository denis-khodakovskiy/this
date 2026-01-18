<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App\Services;

class MyService implements MyServiceInterface
{
    public function getRand(): int
    {
        return mt_rand(0, 100);
    }

    public function translate(string $message, array $params = [], ?string $locale = null): string
    {
        return strtr($message, $params);
    }
}
