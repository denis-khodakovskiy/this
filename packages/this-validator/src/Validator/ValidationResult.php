<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Validator\Validator;

final readonly class ValidationResult
{
    /**
     * @param array<non-empty-string|array-key, non-empty-string|array<non-empty-string, non-empty-string>> $errors
     * @param array<non-empty-string, non-empty-string> $data
     */
    public function __construct(
        public array $errors,
        public mixed $data,
    ) {
    }

    public function isValid(): bool
    {
        return empty($this->errors);
    }
}