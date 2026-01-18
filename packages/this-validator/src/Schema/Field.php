<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Validator\Schema;

use This\Validator\Constraint\AbstractConstraint;
use This\Validator\FieldType\AbstractFieldType;

final readonly class Field
{
    /**
     * @param array<AbstractConstraint> $constraints
     */
    public function __construct(
        public string $name,
        public AbstractFieldType $type,
        public mixed $defaultValue = null,
        public bool $required = false,
        public array $constraints = [],
    ) {
    }
}
