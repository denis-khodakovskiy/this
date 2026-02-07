<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\TemplateEngine;

use This\TemplateEngine\View\AbstractView;

interface TemplateEngineInterface
{
    //public function partialRender(string $viewPath, array $params = []): string;

    public function renderView(AbstractView $view): ?string;
}
