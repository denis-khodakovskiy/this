<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\TemplateEngine;

use This\TemplateEngine\Contracts\TemplateEngineInterface;
use This\TemplateEngine\Contracts\ViewInterface;

final readonly class TemplateEngine implements TemplateEngineInterface
{
    public function __construct(
        private string $viewsDirectoryPath,
    ) {
    }

    public function partialRender(string $viewPath, array $params = []): string
    {
        return $this->renderTemplate($viewPath, $params);
    }

    public function renderView(ViewInterface $view): string
    {
        $content = $this->renderTemplate($view->template(), $view->params());

        return '';
    }

    public function renderTemplate(string $viewPath, array $params = []): string
    {
        try {
            if (!empty($params)) {
                extract($params);
            }

            $viewFilePath = rtrim($this->viewsDirectoryPath, '/') . '/' . trim($viewPath, '/') . '.php';

            if (
                !file_exists($viewFilePath)
                || !is_file($viewFilePath)
                || !is_readable($viewFilePath)
            ) {
                throw new \RuntimeException("View file {$viewFilePath} does not exist or is not readable");
            }

            ob_start();
            include $viewFilePath;
        } finally {
            return ob_get_clean() ?: '';
        }
    }
}
