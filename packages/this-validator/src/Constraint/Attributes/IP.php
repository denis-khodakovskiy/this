<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Validator\Constraint\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
final readonly class IP implements ConstraintAttributeInterface
{
    public function getValidatorFQCN(): string
    {
        return \This\Validator\Constraint\IP::class;
    }
}
