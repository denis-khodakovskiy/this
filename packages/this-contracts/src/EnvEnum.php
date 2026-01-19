<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Contracts;

enum EnvEnum: string
{
    case DEV = 'dev';
    case PROD = 'prod';
    case TEST = 'test';
}
