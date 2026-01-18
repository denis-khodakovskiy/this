<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App\This\Core\Kernel;

use App\Enums\EnvEnum;
use App\This\Infrastructure\Config\PathResolver;

final readonly class KernelConfig
{
    private PathResolver $paths;

    public function __construct(
        public string $appDir,
        public string $varDir,
        public EnvEnum $env = EnvEnum::DEV,
        public bool $debug = true,
    ) {
        $this->paths = new PathResolver(aliases: [
            '%app%' => rtrim($appDir, '/'),
            '%var%' => rtrim($varDir, '/'),
        ]);
    }

    public function path(string $path): string
    {
        return $this->paths->resolve(path: $path);
    }
}
