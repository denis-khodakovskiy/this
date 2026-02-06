<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App\Handlers\CLI;

final readonly class CLIIndexHandler
{
    public function __invoke(): void
    {
        echo sprintf(<<<CLI
            THIS: That Handles It Somehow
            Congratulations! It works!
            %s handler was executed
            You can find it here: %s
            CLI,
                __METHOD__,
                __FILE__,
            ) . PHP_EOL;;
    }
}
