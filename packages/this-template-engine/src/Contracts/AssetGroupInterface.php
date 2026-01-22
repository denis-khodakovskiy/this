<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\TemplateEngine\Contracts;

interface AssetGroupInterface
{
    /**
     * @return array<array-key, non-empty-string>
     */
    public function getCss(): array;

    /**
     * @return array<array-key, non-empty-string>
     */
    public function getJs(): array;

    /**
     * @return array<array-key, non-empty-string>
     */
    public function getDependencies(): array;
}
