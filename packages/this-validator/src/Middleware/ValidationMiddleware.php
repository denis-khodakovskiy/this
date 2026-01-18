<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Validator\Middleware;

use App\This\Core\Enums\RequestMethodsEnum;
use This\Contracts\ContextInterface;
use This\Contracts\MiddlewareInterface;
use This\Validator\Exception\ValidationException;
use This\Validator\Schema\FormSchemaInterface;
use This\Validator\Validator\Validator;

class ValidationMiddleware implements MiddlewareInterface
{
    /**
     * @throws ValidationException
     */
    public function __invoke(ContextInterface $context, callable $next): void
    {
        foreach ($context->getRoute()->getMeta() as $meta) {
            if (is_subclass_of($meta, FormSchemaInterface::class)) {
                $formSchema = new $meta();

                $request = $context->getRequest();
                $input = match (true) {
                    $context->isCli() => $request->getAttributes(),
                    $request->getMethod() === RequestMethodsEnum::GET => $request->get(),
                    $request->getMethod() === RequestMethodsEnum::POST => $request->post(),
                    default => $request->getBodyParameters(),
                };

                $result = (new Validator())->validate(schema: $formSchema, input: $input);

                if (!$result->isValid()) {
                    throw ValidationException::fromValidationResult(validationResult: $result);
                }
            }
        }

        $next($context);
    }
}
