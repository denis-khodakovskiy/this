<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Validator\Constraint;

final class MaxValue extends AbstractConstraint
{
    public function __construct(
        private readonly int $max,
        \Closure|string|null $message = null,
    ) {
        parent::__construct($message);
    }

    public function validate(mixed $value): ?string
    {
        if (!is_int($value)) {
            return null;
        }

        return $value > $this->max
            ? $this->message() ?? "Value must be less or equal to {$this->max}"
            : null;
    }
}
