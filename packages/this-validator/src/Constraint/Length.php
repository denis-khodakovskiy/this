<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Validator\Constraint;

final class Length extends AbstractConstraint
{
    public function __construct(
        private readonly ?int $min = null,
        private readonly ?int $max = null,
        \Closure|string|null $message = null,
    ) {
        parent::__construct($message);
    }

    public function validate(mixed $value): ?string
    {
        if (!is_string($value) && !is_array($value)) {
            return null;
        }

        if (
            $this->min !== null
            && (
                is_string($value) && mb_strlen($value) < $this->min
                || is_array($value) && count($value) < $this->min
            )
        ) {
            return $this->message() ?? "Minimum length is {$this->min}";
        }

        if (
            $this->max !== null
            && (
                is_string($value) && mb_strlen($value) > $this->max
                || is_array($value) && count($value) > $this->max
            )
        ) {
            return $this->message() ?? "Maximum length is {$this->max}";
        }

        return null;
    }
}
