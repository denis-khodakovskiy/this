<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Validator\Constraint;

final class Length extends AbstractConstraint
{
    public function __construct(
        public readonly ?int $min = null,
        public readonly ?int $max = null,
    ) {
    }

    public function validate(mixed $value): bool
    {
        if (!is_string($value) && !is_array($value)) {
            return false;
        }

        return match (true) {
            is_string($value) => mb_strlen($value) >= $this->min && mb_strlen($value) <= $this->max,
            default => count($value) >= $this->min && count($value) <= $this->max,
        };
    }
}
