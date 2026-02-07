<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\TemplateEngine\Renderer;

interface ViewRendererRegistryInterface
{
    public function registerRenderer(ViewRendererInterface $renderer): self;

    /**
     * @return array<array-key, ViewRendererInterface>
     */
    public function getRenderers(): array;
}
