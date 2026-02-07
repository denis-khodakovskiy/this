<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\TemplateEngine\View;

use This\TemplateEngine\Asset\AssetInterface;

readonly class ComposedView extends AbstractView
{
    /**
     * @param array<non-empty-string, non-empty-string> $params
     * @param array<array-key, class-string> $assets
     * @param array<non-empty-string, mixed> $vars
     */
    public function __construct(
        public string $template,
        public ?string $title = null,
        public ?string $layout = null,
        public array $params = [],
        public array $assets = [],
        array $vars = [],
    ) {
        parent::__construct($vars);
    }
}
