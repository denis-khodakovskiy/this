<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\TemplateEngine\Contracts;

interface AssetsRegistryInterface
{
    public function addAsset(AssetInterface $asset): self;

    public function getAsset(): AssetInterface;

    public function assets(): array;
}
