<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Validator\Constraint;

final class JSON extends AbstractConstraint
{
    public function validate(mixed $value): bool
    {
        $result = json_decode($value);

        return $result !== false && $result !== null;
    }
}
