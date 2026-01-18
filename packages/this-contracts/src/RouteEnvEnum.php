<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Contracts;

enum RouteEnvEnum: string
{
    case CLI = 'cli';
    case HTTP = 'http';
}
