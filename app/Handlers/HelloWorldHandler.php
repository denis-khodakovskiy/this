<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App\Handlers;

final readonly class HelloWorldHandler
{
    public function __invoke(): void
    {
        echo 'Hello World!' . PHP_EOL;
    }
}
