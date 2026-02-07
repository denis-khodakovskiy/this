<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\TemplateEngine\Renderer;

use This\TemplateEngine\View\ClosureView;
use This\TemplateEngine\View\AbstractView;

final class ClosureRenderer implements ViewRendererInterface
{
    public function supports(AbstractView $view): bool
    {
        return $view instanceof ClosureView;
    }

    public function render(AbstractView $view, RenderContext $context): string
    {
        assert($view instanceof ClosureView);

        return ($view->closure)();
    }
}
