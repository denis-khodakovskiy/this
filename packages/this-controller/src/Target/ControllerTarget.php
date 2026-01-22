<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Controller\Target;

use This\Contracts\ExecutableTargetInterface;

final readonly class ControllerTarget implements ExecutableTargetInterface
{
    public function __construct(
        private \Closure $closure,
    ) {
    }

    public function execute(): mixed
    {
        return ($this->closure)();
    }
}
