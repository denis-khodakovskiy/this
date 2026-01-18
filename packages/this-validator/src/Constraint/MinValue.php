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
        \Closure|string|null $message = null,
    ) {
        parent::__construct($message);
    }

    public function validate(mixed $value): ?string
    {
        if (!is_int($value)) {
            return null;
        }

        return $value < $this->min
            ? $this->message() ?? "Value must be greater or equal to {$this->min}"
            : null;
    }
}
