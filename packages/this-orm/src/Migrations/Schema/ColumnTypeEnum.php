<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\Migrations\Schema;

enum ColumnTypeEnum: string
{
    case STRING = 'string';
    case INTEGER = 'integer';
    case FLOAT = 'float';
    case BOOLEAN = 'boolean';
    case TEXT = 'text';
    case DATETIME = 'datetime';
    case TIMESTAMP = 'timestamp';
    case JSON = 'json';
    case ARRAY = 'array';
}
