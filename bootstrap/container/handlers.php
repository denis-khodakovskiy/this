<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

use App\Handlers\CLI\CLIIndexHandler;
use App\Handlers\HTTP\HTTPIndexHandler;
use This\Contracts\ContainerInterface;
use This\Output\CLI\CLIMarkupRenderer;
use This\Output\CLI\CLIOutput;

return function (ContainerInterface $container): void {
    $container
        ->bind(
            id: HTTPIndexHandler::class,
            definition: static fn (ContainerInterface $container) => new HTTPIndexHandler(),
        )
        ->bind(
            id: CLIIndexHandler::class,
            definition: static fn (ContainerInterface $container) => new CLIIndexHandler(
                new CLIOutput(new CLIMarkupRenderer()),
            ),
        )
    ;
};
