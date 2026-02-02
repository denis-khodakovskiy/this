<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App\This\Core\Env;

use This\Contracts\EnvContainerInterface;

final class EnvContainer implements EnvContainerInterface
{
    private array $vars = [];

    public function __construct(string $path)
    {
        if (!is_file($path)) {
            return;
        }

        foreach (file($path, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES) as $line) {
            if (str_starts_with(trim($line), '#')) {
                continue;
            }

            if (!str_contains($line, '=')) {
                continue;
            }

            [$key, $value] = explode('=', $line, 2);

            $this->vars[trim($key)] = $this->normalize(trim($value));
        }
    }

    public function get(string $key, mixed $default = null): mixed
    {
        return $this->vars[$key]
            ?? $_ENV[$key]
            ?? $_SERVER[$key]
            ?? $default;
    }

    public function has(string $key): bool
    {
        return array_key_exists($key, $this->vars)
            || isset($_ENV[$key])
            || isset($_SERVER[$key]);
    }

    private function normalize(string $value): mixed
    {
        if ($value === 'true') return true;
        if ($value === 'false') return false;
        if ($value === 'null') return null;

        return trim($value, "\"'");
    }
}