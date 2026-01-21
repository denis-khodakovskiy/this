<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App\DDDContext\Application\CommandHandler;

use App\DDDContext\Application\Command\MyCommand;

final readonly class MyCommandHandler
{
    public function __invoke(MyCommand $command): void
    {
        var_dump(__METHOD__);
    }
}
