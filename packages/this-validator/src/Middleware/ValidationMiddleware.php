<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Validator\Middleware;

use This\Contracts\RequestContextInterface;
use This\Contracts\MiddlewareInterface;
use This\Contracts\RequestMethodsEnum;
use This\Validator\Exception\ValidationException;
use This\Validator\Schema\FormSchemaInterface;
use This\Validator\Validator\Validator;

class ValidationMiddleware implements MiddlewareInterface
{
    /**
     * @throws ValidationException
     */
    public function __invoke(RequestContextInterface $context, callable $next): void
    {
        $next($context);
    }
}
