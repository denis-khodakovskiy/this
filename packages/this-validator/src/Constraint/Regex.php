<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Validator\Constraint;

final class Regex extends AbstractConstraint
{
    public function __construct(
        private readonly string $pattern,
        \Closure|string|null $message = null,
    ) {
        parent::__construct($message);
    }

    public function validate(mixed $value): ?string
    {
        return !preg_match($this->pattern, $value)
            ? $this->message() ?? 'Value does not match pattern'
            : null;
    }
}
