<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App\Handlers\CLI;

use This\Output\CLI\CLIOutput;

final readonly class CLIIndexHandler
{
    public function __construct(
        private CLIOutput $output,
    ) {
    }

    public function __invoke(): void
    {
        $this->output->info('THIS: That Handles It Somehow');
        $this->output->info('Congratulations! It works!');
        $this->output->info(sprintf('<b>%s</b> has been executed', __METHOD__));
        $this->output->info(sprintf('You can find it here: <u>%s</u>', __FILE__));

        exit(1);
    }
}
