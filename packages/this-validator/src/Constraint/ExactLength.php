<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Validator\Constraint;

final class ExactLength extends AbstractConstraint
{
    public function __construct(
        public readonly int $length,
    ) {
    }

    public function validate(mixed $value): bool
    {
        return mb_strlen($value) === $this->length;
    }
}
