<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Validator\Exception;

use This\Validator\Validator\ValidationResult;

final class ValidationException extends \Exception
{
    public function __construct(
        string $message = "",
        int $code = 0,
        ?\Throwable $previous = null,
        public readonly ?ValidationResult $validationResult = null,
    ) {
        parent::__construct($message, $code, $previous);
    }

    public static function fromValidationResult(ValidationResult $validationResult): self
    {
        return new self(
            message: 'Validation failed',
            code: 400,
            validationResult: $validationResult,
        );
    }
}
