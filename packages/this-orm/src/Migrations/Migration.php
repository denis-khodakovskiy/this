<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\Migrations;

use This\ORM\Migrations\Schema\SchemaBuilderInterface;

abstract class Migration
{
    abstract public function up(SchemaBuilderInterface $schemaBuilder): void;

    abstract public function down(SchemaBuilderInterface $schemaBuilder): void;

    public function draft(): bool
    {
        return true;
    }

    public function draftReason(): ?string
    {
        return null;
    }
}
