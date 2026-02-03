<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\Migrations\Registry;

use This\ORM\Schema\SchemaTableInterface;

final class RegistrySchema implements SchemaTableInterface
{
    public static function getTableName(): string
    {
        return 'migrations';
    }
}
