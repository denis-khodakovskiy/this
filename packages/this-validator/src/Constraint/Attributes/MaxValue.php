<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Validator\Constraint\Attributes;

use  Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
final readonly class MaxValue implements ConstraintAttributeInterface
{
    public function __construct(
        public int $max,
    ) {
    }

    public function getValidatorFQCN(): string
    {
        return \This\Validator\Constraint\MaxValue::class;
    }
}
