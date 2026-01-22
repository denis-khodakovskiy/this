<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\TemplateEngine\Contracts;

interface TemplateEngineInterface
{
    public function partialRender(string $viewPath, array $params = []): string;

    public function renderView(ViewInterface $view): string;
}
