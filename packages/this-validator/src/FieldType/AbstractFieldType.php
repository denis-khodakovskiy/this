<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Validator\FieldType;

abstract class AbstractFieldType
{
    public function __construct(
        protected ?string $message = null,
    ) {
    }

    abstract public function normalize(mixed $value): mixed;

    abstract public function validateType(mixed $value): ?string;
}
