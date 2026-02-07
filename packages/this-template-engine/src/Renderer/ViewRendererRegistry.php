<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\TemplateEngine\Renderer;

final class ViewRendererRegistry implements ViewRendererRegistryInterface
{
    /** @var array<array-key, ViewRendererInterface> */
    private array $renderers = [];

    public function registerRenderer(ViewRendererInterface $renderer): self
    {
        if (!in_array($renderer, $this->renderers, true)) {
            $this->renderers[] = $renderer;
        }

        return $this;
    }

    public function getRenderers(): array
    {
        return $this->renderers;
    }
}
