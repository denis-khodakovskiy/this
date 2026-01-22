<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App\This\Core\Invoker;

use This\Contracts\ExecutableTargetInterface;

final readonly class InvokableTarget implements ExecutableTargetInterface
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
