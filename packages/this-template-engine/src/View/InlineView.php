<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\TemplateEngine\View;

readonly class InlineView extends AbstractView
{
    public function __construct(
        public string $template,
        public array $params = [],
        public array $assets = [],
        array $vars = [],
    ) {
        parent::__construct($vars);
    }
}
