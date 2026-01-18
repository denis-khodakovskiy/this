<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Validator\Validator;

use This\Validator\Constraint\Required;
use This\Validator\Schema\FormSchemaInterface;

final readonly class Validator implements ValidatorInterface
{
    public function validate(FormSchemaInterface $schema, array $input): ValidationResult
    {
        $errors = [];
        $data = [];

        foreach ($schema->fields() as $field) {
            $value = $input[$field->name] ?? null;

            if ($field->required) {
                $requiredError = (new Required())->validate($value);

                if ($requiredError !== null) {
                    $errors[$field->name][] = $requiredError;

                    continue;
                }
            }

            if ($value === null) {
                continue;
            }

            $value = $field->type->normalize($value);
            $typeError = $field->type->validateType($value);

            if ($typeError !== null) {
                $errors[$field->name][] = $typeError;

                continue;
            }

            foreach ($field->constraints as $constraint) {
                $error = $constraint->validate($value);

                if ($error !== null) {
                    $errors[$field->name][] = $error;
                }
            }

            $data[$field->name] = $value;
        }

        return new ValidationResult($errors, $data);
    }
}
