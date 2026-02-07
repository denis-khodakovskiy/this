<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\TemplateEngine\Renderer;

use This\TemplateEngine\View\AbstractView;

interface ViewRendererInterface
{
    public function supports(AbstractView $view): bool;

    public function render(AbstractView $view, RenderContext $context): string;
}
