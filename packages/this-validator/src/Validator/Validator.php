<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Validator\Validator;

use This\Validator\Constraint\AbstractConstraint;
use This\Validator\Constraint\Attributes\ConstraintAttributeInterface;
use This\Validator\Constraint\Required;
use This\Validator\Schema\FormSchemaInterface;

final readonly class Validator implements ValidatorInterface
{
    public function validate(object $object): ValidationResult
    {
        if (!is_object($object)) {
            throw new \RuntimeException('Invalid object provided');
        }

        $violations = [];

        $reflection = new \ReflectionClass($object);
        foreach ($reflection->getProperties() as $property) {
            foreach ($property->getAttributes() as $attribute) {
                /** @var ConstraintAttributeInterface $attributeInstance */
                $attributeInstance = $attribute->newInstance();

                if (!$attributeInstance instanceof ConstraintAttributeInterface) {
                    continue;
                }

                $args = get_object_vars($attributeInstance);
                $validatorClass = $attributeInstance->getValidatorFQCN();

                if ([] !== $args) {
                    $validator = new $validatorClass(
                        ...$args,
                    );
                } else {
                    $validator = new $validatorClass();
                }

                if (!$validator instanceof AbstractConstraint) {
                    continue;
                }

                $value = $property->getValue($object);

                if (!$validator->validate($value)) {
                    $violations[$property->getName()] = new Violation(
                        rule: $validator->getConstraintCode(),
                        value: $value,
                        params: $args,
                    );
                }
            }
        }

        return new ValidationResult(violations: $violations);
    }

    public function validateInput(FormSchemaInterface $schema, array $input): ValidationResult
    {
        $violations = [];

        foreach ($schema->fields() as $field) {
            $value = $input[$field->name] ?? null;

            if ($field->required) {
                $required = (new Required())->validate(value: $value);

                if (!$required) {
                    $violations[$field->name] = new Violation(
                        rule: 'required',
                        value: null,
                    );

                    continue;
                }
            }

            if ($value === null) {
                continue;
            }

            foreach ($field->constraints as $constraint) {
                if (!$constraint->validate($value)) {
                    $violations[$field->name] = new Violation(
                        rule: $constraint->getConstraintCode(),
                        value: null,
                        params: get_object_vars($constraint),
                    );
                }
            }
        }

        return new ValidationResult(violations: $violations);
    }
}
