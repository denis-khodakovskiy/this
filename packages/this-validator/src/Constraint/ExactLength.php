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
        \Closure|string|null $message = null,
    ) {
        parent::__construct($message);
    }

    public function validate(mixed $value): ?string
    {
        return mb_strlen($value) !== $this->length
            ? $this->message() ?? "Value must be exactly {$this->length} characters"
            : null;
    }
}
