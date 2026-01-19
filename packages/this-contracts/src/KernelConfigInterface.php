<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Contracts;

interface KernelConfigInterface
{
    public function path(string $path): string;

    public function getDefaultLocale(): string;
}
