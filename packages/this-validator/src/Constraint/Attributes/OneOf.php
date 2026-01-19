<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Validator\Constraint\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_PROPERTY)]
final readonly class OneOf implements ConstraintAttributeInterface
{
    /**
     * @param array<mixed> $allowed
     */
    public function __construct(
        public array $allowed = [],
    ) {
    }

    public function getValidatorFQCN(): string
    {
        return \This\Validator\Constraint\OneOf::class;
    }
}
