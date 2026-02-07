<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\TemplateEngine\Renderer;

use This\TemplateEngine\View\InlineView;
use This\TemplateEngine\View\AbstractView;

final class InlineRenderer implements ViewRendererInterface
{
    public function supports(AbstractView $view): bool
    {
        return $view instanceof InlineView;
    }

    public function render(AbstractView $view, RenderContext $context): string
    {
        assert($view instanceof InlineView);

        return $view->template;
    }
}
