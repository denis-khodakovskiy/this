<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\TemplateEngine\Renderer;

use This\Contracts\RequestInterface;
use This\TemplateEngine\TemplateEngineInterface;

final readonly class RenderContext
{
    public function __construct(
        private TemplateEngineInterface $engine,
        private RequestInterface $request,
        private array $vars = [],
    ) {
    }

    public function engine(): TemplateEngineInterface
    {
        return $this->engine;
    }

    public function request(): RequestInterface
    {
        return $this->request;
    }

    public function var(string $key): mixed
    {
        return $this->vars[$key] ?? null;
    }
}
