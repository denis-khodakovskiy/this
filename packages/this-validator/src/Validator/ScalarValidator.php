<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Validator\Validator;

use This\Validator\Constraint\AbstractConstraint;
use This\Validator\FieldType\AbstractFieldType;

final class ScalarValidator
{
    /**
     * @param array<AbstractConstraint> $constraints
     */
    public static function validate(
        mixed $value,
        AbstractFieldType $fieldType,
        array $constraints = [],
    ): ValidationResult {
        $errors = [];

        $value = $fieldType->normalize($value);
        $typeError = $fieldType->validateType($value);

        if ($typeError) {
            $errors[] = $typeError;
        }

        foreach ($constraints as $constraint) {
            $error = $constraint->validate($value);

            if ($error !== null) {
                $errors[] = $error;
            }
        }

        return new ValidationResult(errors: $errors, data: $value);
    }
}
