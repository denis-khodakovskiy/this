<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Validator\Constraint;

final class MinValue extends AbstractConstraint
{
    public function __construct(
        private readonly int $min,
    ) {
    }

    public function validate(mixed $value): bool
    {
        if (!is_int($value)) {
            return false;
        }

        return $value >= $this->min;
    }
}
