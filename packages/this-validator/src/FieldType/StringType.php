<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Validator\FieldType;

class StringType extends AbstractFieldType
{
    public function normalize(mixed $value): mixed
    {
        return is_scalar($value) ? (string)$value : $value;
    }

    public function validateType(mixed $value): ?string
    {
        return is_string($value) ? null : ($this->message ?? 'Value must be a string');
    }
}
