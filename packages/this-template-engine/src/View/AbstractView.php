<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\TemplateEngine\View;

abstract readonly class AbstractView
{
    /**
     * @param array<non-empty-string, mixed> $vars
     */
    public function __construct(
        public array $vars = [],
    ) {
    }
}
