<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Validator\Middleware\Internal;

use This\Contracts\MiddlewareInterface;
use This\Validator\Pipeline\ValidationContextInterface;

class ExecutionMiddleware implements MiddlewareInterface
{
    public function __invoke(ValidationContextInterface $validationContext, callable $next)
    {

    }
}
