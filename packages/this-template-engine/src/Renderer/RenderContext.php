<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\TemplateEngine\Renderer;

use This\Contracts\RequestInterface;
use This\TemplateEngine\Asset\AssetManager;
use This\TemplateEngine\TemplateEngineInterface;

final readonly class RenderContext
{
    public function __construct(
        public TemplateEngineInterface $engine,
        public RequestInterface $request,
        public AssetManager $assets,
        private array $vars = [],
    ) {
    }

    public function var(string $key): mixed
    {
        return $this->vars[$key] ?? null;
    }
}
