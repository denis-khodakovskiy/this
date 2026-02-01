<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\DQL\AST;

enum ComparisonOperatorEnum: string
{
    case EQUAL  = '=';
    case NOT_EQUAL = '!=';
    case GRATER_THAN  = '>';
    case GRATER_THAN_OR_EQUAL = '>=';
    case LESS_THAN  = '<';
    case LESS_THAN_OR_EQUAL = '<=';
    case LIKE = 'LIKE';
    case NOT_LIKE = 'NOT LIKE';
}
