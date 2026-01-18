<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Validator\Schema;

interface FormSchemaInterface
{
    public function add(Field $field): self;

    /**
     * @return array<Field>
     */
    public function fields(): array;
}
