<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\Migrations\Schema;

use This\ORM\Migrations\Command\CreateForeignKeyCommand;
use This\ORM\Migrations\Command\CreateIndexCommand;

interface SchemaBuilderInterface
{
    public function createTable(string $name, callable $definition): void;

    public function dropTable(string $name): void;

    public function table(string $name, callable $definition): void;

    public function nonRollBack(): self;
}
