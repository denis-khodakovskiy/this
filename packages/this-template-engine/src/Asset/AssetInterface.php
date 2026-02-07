<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\TemplateEngine\Asset;

interface AssetInterface
{
    /**
     * @return array<array-key, non-empty-string>
     */
    public function css(): array;

    /**
     * @return array<array-key, non-empty-string>
     */
    public function js(): array;

    /**
     * @return array<AssetInterface>
     */
    public function depends(): array;
}
