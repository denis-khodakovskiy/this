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
        array $constraints = [],
    ): ValidationResult {
        $violations = [];

        foreach ($constraints as $constraint) {
            if (!$constraint->validate($value)) {
                $violations[] = new Violation(
                    rule: $constraint->getConstraintCode(),
                    value: $value,
                    params: get_object_vars($constraint),
                );
            }
        }

        return new ValidationResult(violations: $violations);
    }
}
