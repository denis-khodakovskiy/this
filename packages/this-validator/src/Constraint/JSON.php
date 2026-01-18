<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Validator\Constraint;

final class JSON extends AbstractConstraint
{
    public function validate(mixed $value): ?string
    {
        return !json_decode($value)
            ? $this->message() ?? 'Invalid JSON'
            : null;
    }
}
