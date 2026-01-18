<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Validator\FieldType;

class EmailType extends StringType
{
    public function validateType(mixed $value): ?string
    {
        if (!is_string($value)) {
            return 'Value must be a string';
        }

        return filter_var($value, FILTER_VALIDATE_EMAIL)
            ? null
            : ($this->message ?? 'Invalid email format');
    }
}
