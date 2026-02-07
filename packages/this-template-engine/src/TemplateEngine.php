<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\TemplateEngine;

use This\Contracts\RequestProviderInterface;
use This\TemplateEngine\Renderer\RenderContext;
use This\TemplateEngine\Renderer\ViewRendererRegistryInterface;
use This\TemplateEngine\View\AbstractView;

final readonly class TemplateEngine implements TemplateEngineInterface
{
    public function __construct(
        private ViewRendererRegistryInterface $viewRendererRegistry,
        private RequestProviderInterface $requestProvider,
    ) {
    }

    public function renderView(AbstractView $view): ?string
    {
        foreach ($this->viewRendererRegistry->getRenderers() as $renderer) {
            if ($renderer->supports($view)) {
                return $renderer->render($view, new RenderContext(
                    $this,
                    $this->requestProvider->getRequest(),
                    $view->vars,
                ));
            }
        }

        return null;
    }
}
