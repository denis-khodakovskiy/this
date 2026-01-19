<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App\Handlers\Cli;

use Psr\Log\LoggerInterface;

final readonly class IndexHandler
{
    public function __construct(
        private LoggerInterface $logger,
    ) {
    }

    public function __invoke(): void
    {
        $this->logger->info(sprintf('%s has been executed', __METHOD__));

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
