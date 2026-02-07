<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\TemplateEngine\View;

readonly class ClosureView extends AbstractView
{
    public function __construct(
        public \Closure $closure,
        array $vars = [],
    ) {
        parent::__construct($vars);
    }
}
