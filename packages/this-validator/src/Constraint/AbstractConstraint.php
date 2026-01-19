<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Validator\Constraint;

abstract class AbstractConstraint
{
    abstract public function validate(mixed $value): bool;

    public function getConstraintCode(): string
    {
        return static::class;
    }
}
