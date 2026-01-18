<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Validator\Constraint;

final class Required extends AbstractConstraint
{
    public function validate(mixed $value): ?string
    {
        return $value === null || $value === ''
            ? $this->message() ?? 'Field is required'
            : null;
    }
}
