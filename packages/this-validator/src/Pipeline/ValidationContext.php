<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Validator\Pipeline;

use This\Validator\Validator\ValidationResult;

class ValidationContext implements ValidationContextInterface
{
    public function __construct(
        private readonly ValidationResult $validationResult,
        private array $metadata = [],
    ) {
    }

    public function getValidationResult(): ValidationResult
    {
        return $this->validationResult;
    }

    public function addMeta(string $key, mixed $value): void
    {
        $this->metadata[$key] = $value;
    }

    public function getMeta(string $key, mixed $defaultValue = null): mixed
    {
        return $this->metadata[$key] ?? $defaultValue;
    }

    public function hasMeta(string $key): bool
    {
        return isset($this->metadata[$key]);
    }
}
