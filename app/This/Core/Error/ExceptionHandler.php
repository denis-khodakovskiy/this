<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App\This\Core\Error;

use This\Contracts\ContextInterface;
use This\Contracts\ExceptionHandlerInterface;
use This\Validator\Exception\ValidationException;

final readonly class ExceptionHandler implements ExceptionHandlerInterface
{
    public function handle(\Throwable $exception, ContextInterface $context): void
    {
        echo '<pre>';
        echo sprintf(
            "An error occurred: [%d] %s\n%s:%d\nTrace:\n%s",
            $exception->getCode(),
            $exception->getMessage(),
            $exception->getFile(),
            $exception->getLine(),
            $exception->getTraceAsString(),
        );
        echo PHP_EOL;
        if ($exception instanceof ValidationException) {
            foreach ($exception->validationResult->errors as $fieldName => $errors) {
                echo sprintf('%s: %s', $fieldName, implode(', ', $errors)) . PHP_EOL;
            }
        }
        echo '</pre>';
    }
}
