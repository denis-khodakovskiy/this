<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App\Handlers\Http;

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

        echo sprintf(<<<HTML
            <pre style="text-align: center">
                <div>
                    <h2>THIS: That Handles It Somehow</h2>
                    <h3>Congratulations! It works!</h3>
                    <p>%s handler was executed</p>
                    <p>You can find it here: %s</p>
                </div>
            </pre>
            HTML,
            __METHOD__,
            __FILE__,
        );
    }
}
