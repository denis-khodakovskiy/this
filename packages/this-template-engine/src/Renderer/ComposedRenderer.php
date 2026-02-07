<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\TemplateEngine\Renderer;

use This\TemplateEngine\View\ComposedView;
use This\TemplateEngine\View\AbstractView;

final readonly class ComposedRenderer implements ViewRendererInterface
{
    public function __construct(
        private string $viewsPath,
    ) {
    }

    public function supports(AbstractView $view): bool
    {
        return $view instanceof ComposedView;
    }

    public function render(AbstractView $view, RenderContext $context): string
    {
        assert($view instanceof ComposedView);

        if ($view->assets !== []) {
            $context->assets->register($view->assets);
        }

        $content = $this->renderFile($view->template, array_merge($view->params, ['context' => $context]));

        if ($view->layout) {
            return $this->renderFile($view->layout, ['content' => $content, 'context' => $context]);
        }

        return $content;
    }

    private function renderFile(string $file, array $params = []): string
    {
        try {
            if (!empty($params)) {
                extract($params);
            }

            $viewFilePath = rtrim($this->viewsPath, '/') . '/' . trim($file, '/') . '.php';

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
