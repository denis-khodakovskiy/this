<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Validator\Constraint;

final class IP extends AbstractConstraint
{
    public function validate(mixed $value): ?string
    {
        return filter_var($value, FILTER_VALIDATE_IP)
            ? $this->message() ?? 'Invalid IP'
            : null;
    }
}
