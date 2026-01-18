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
        \Closure|string|null $message = null,
    ) {
        parent::__construct($message);
    }

    public function validate($value): ?string
    {
        return $value < $this->min || $value > $this->max
            ? $this->message() ?? "Value must be between {$this->min} and {$this->max}"
            : null;
    }
}
