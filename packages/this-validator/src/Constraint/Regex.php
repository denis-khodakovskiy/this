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
    ) {
    }

    public function validate(mixed $value): bool
    {
        return preg_match($this->pattern, $value);
    }
}
