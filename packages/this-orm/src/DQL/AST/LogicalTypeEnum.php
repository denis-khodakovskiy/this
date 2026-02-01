<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\DQL\AST;

enum LogicalTypeEnum: string
{
    case AND = 'AND';
    case OR = 'OR';
}
