<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Contracts;

interface KernelConfigProviderInterface
{
    public function setConfig(KernelConfigInterface $config): void;

    public function getConfig(): KernelConfigInterface;
}
