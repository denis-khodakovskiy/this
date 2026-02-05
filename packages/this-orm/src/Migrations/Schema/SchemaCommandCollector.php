<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\Migrations\Schema;

final class SchemaCommandCollector
{
    /** @var array<MigrationCommandInterface> */
    private array $commands = [];

    public function addCommand(MigrationCommandInterface $command): void
    {
        $this->commands[] = $command;
    }

    public function getCommands(): array
    {
        return $this->commands;
    }
}
