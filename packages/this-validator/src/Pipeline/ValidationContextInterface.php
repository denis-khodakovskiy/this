<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Validator\Pipeline;

use This\Validator\Validator\ValidationResult;

interface ValidationContextInterface
{
    public function getValidationResult(): ValidationResult;

    public function addMeta(string $key, mixed $value): void;

    public function getMeta(string $key, mixed $defaultValue = null): mixed;

    public function hasMeta(string $key): bool;
}
