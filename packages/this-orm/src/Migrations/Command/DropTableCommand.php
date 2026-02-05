<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\Migrations\Command;

final readonly class DropTableCommand
{
    public function __construct(
        public string $tableName,
    ) {
    }
}
