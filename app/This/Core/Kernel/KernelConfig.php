<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App\This\Core\Kernel;

use App\This\Infrastructure\Config\PathResolver;
use This\Contracts\EnvEnum;
use This\Contracts\KernelConfigInterface;

final readonly class KernelConfig implements KernelConfigInterface
{
    private PathResolver $paths;

    public function __construct(
        public string $rootDir,
        public string $appDir,
        public string $varDir,
        public EnvEnum $env = EnvEnum::DEV,
        public bool $debug = true,
        public string $defaultLocale = 'en',
    ) {
        $this->paths = new PathResolver(aliases: [
            '%root%' => rtrim($rootDir, '/'),
            '%app%' => rtrim($appDir, '/'),
            '%var%' => rtrim($varDir, '/'),
        ]);
    }

    public function path(string $path): string
    {
        return $this->paths->resolve(path: $path);
    }

    public function getDefaultLocale(): string
    {
        return $this->defaultLocale;
    }
}
