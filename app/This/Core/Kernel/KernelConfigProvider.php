<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App\This\Core\Kernel;

use This\Contracts\KernelConfigInterface;
use This\Contracts\KernelConfigProviderInterface;

class KernelConfigProvider implements KernelConfigProviderInterface
{
    private ?KernelConfigInterface $kernelConfig = null;

    public function getConfig(): KernelConfigInterface
    {
        if (!$this->kernelConfig) {
            throw new \RuntimeException('KernelConfigResolver not initialized');
        }

        return $this->kernelConfig;
    }

    public function setConfig(KernelConfigInterface $config): void
    {
        $this->kernelConfig = $config;
    }
}
