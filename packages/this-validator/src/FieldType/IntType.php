<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Validator\FieldType;

class IntType extends AbstractFieldType
{
    public function normalize(mixed $value): mixed
    {
        return is_numeric($value) ? (int)$value : $value;
    }

    public function validateType(mixed $value): ?string
    {
        return is_int($value) ? null : ($this->message ?? 'Value must be an integer');
    }
}
