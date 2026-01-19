<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Validator\Constraint;

final class Range extends AbstractConstraint
{
    public function __construct(
        private readonly int $min,
        private readonly int $max,
    ) {
    }

    public function validate($value): bool
    {
        return $value >= $this->min && $value <= $this->max;
    }
}
