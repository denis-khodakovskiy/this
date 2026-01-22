<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\TemplateEngine\Contracts;

interface ViewInterface
{
    public function template(): string;

    public function params(): array;
}
