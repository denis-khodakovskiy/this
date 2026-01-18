<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App\This\Core\Request\Resolvers;

use App\This\Core\Request\Request;
use This\Contracts\RequestInterface;
use This\Contracts\RequestResolverInterface;

final readonly class CLIRequestResolver implements RequestResolverInterface
{
    public function supports(): bool
    {
        return PHP_SAPI === 'cli' || PHP_SAPI === 'phpdbg';
    }

    public function resolve(): RequestInterface
    {
        $arguments = $_SERVER['argv'];
        array_shift($arguments); //path, e.g. bin/this
        $commandPath = array_shift($arguments);
        $params = [];
        $position = 0;

        for ($i = 0; $i <= count($arguments) - 1; $i++) {
            $arg = $arguments[$i];

            if (str_starts_with($arg, '--')) {
                if (str_contains($arg, '=')) {
                    [$key, $value] = explode('=', $arg, 2);
                } else {
                    $key = $arg;
                    $value = true;
                }
                $params[ltrim($key, '-')] = $value;

                continue;
            }

            if (str_starts_with($arg, '-')) {
                $key = ltrim($arg, '-');
                $value = $args[$i + 1] ?? null;

                if ($value === null || str_starts_with($value, '-')) {
                    $params[$key] = true;
                } else {
                    $params[$key] = $value;
                    $i++;
                }

                continue;
            }

            $params[$position] = $arg;
            $position++;
        }

        return (new Request())
            ->setPath($commandPath)
            ->setAttributes($params)
        ;
    }
}
