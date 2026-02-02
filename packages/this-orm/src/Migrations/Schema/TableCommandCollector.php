<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\Migrations\Schema;

final class TableCommandCollector
{
    /** @var array<CommandInterface> */
    private array $commands = [];

    public function addCommand(CommandInterface $command): void
    {
        $this->commands[] = $command;
    }

    /**
     * @return array<CommandInterface>
     */
    public function getCommands(): array
    {
        return $this->commands;
    }
}
