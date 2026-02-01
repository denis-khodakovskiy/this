<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\Schema;

interface SchemaTableInterface
{
    public static function getTableName(): string;
}
