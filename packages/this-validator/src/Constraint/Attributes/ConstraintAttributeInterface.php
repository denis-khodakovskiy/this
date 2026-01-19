<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Validator\Constraint\Attributes;

interface ConstraintAttributeInterface
{
    public function getValidatorFQCN(): string;
}
