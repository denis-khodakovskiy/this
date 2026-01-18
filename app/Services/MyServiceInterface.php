<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App\Services;

interface MyServiceInterface
{
    public function getRand(): int;

    public function translate(string $message, array $params = [], ?string $locale = null): string;
}
