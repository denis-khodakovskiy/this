<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Orm\Compiler;

use This\ORM\DQL\Compiled\Token\ParamToken;

final class ParametersBag
{
    private int $index = 0;

    private array $parameters = [];

    public function next($value): string
    {
        $token = 'p' . ++$this->index;

        $this->parameters[$token] = $value;

        return ":{$token}";
    }

    public function getParameters(): array
    {
        return $this->parameters;
    }
}
