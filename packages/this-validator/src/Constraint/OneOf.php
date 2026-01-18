<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Validator\Constraint;

final class OneOf extends AbstractConstraint
{
    /**
     * @param array<mixed> $allowed
     */
    public function __construct(
        private readonly array $allowed = [],
        \Closure|string|null $message = null,
    ) {
        parent::__construct($message);
    }

    public function validate(mixed $value): ?string
    {
        return !in_array($value, $this->allowed, true)
            ? $this->message() ?? 'Invalid value'
            : null;
    }
}
