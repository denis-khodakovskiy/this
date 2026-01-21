<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App\Handlers;

use App\DDDContext\Application\Command\MyCommand;
use App\DDDContext\Application\Query\MyQuery;
use This\Messenger\Messenger\MessengerInterface;

final readonly class MessengerTestHandler
{
    public function __construct(
        private MessengerInterface $messenger,
    ) {
    }

    public function __invoke(): void
    {
        $this->messenger->dispatch(message: new MyCommand(
            'Denis',
            40,
            172,
            70,
            'yellow',
            'male'
        ));

        var_dump($this->messenger->ask(message: new MyQuery(
            18454,
        )));
    }
}
