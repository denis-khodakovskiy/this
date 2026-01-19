<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Validator\Validator;

final readonly class ValidationResult
{
    /**
     * @param array<Violation> $violations
     */
    public function __construct(
        public array $violations = [],
    ) {
    }

    public function isValid(): bool
    {
        return empty($this->errors);
    }
}