<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Validator\Constraint;

abstract class AbstractConstraint
{
    public function __construct(
        private readonly \Closure|string|null $message = null,
    ) {
    }

    abstract public function validate(mixed $value): ?string;

    protected function message(): string|null
    {
        return match (true) {
            $this->message instanceof \Closure => ($this->message)(),
            is_string($this->message) => $this->message,
            default => null,
        };
    }
}
