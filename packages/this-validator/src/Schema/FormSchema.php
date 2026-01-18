<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Validator\Schema;

class FormSchema implements FormSchemaInterface
{
    /** @var array<Field> */
    private array $fields = [];

    public function __construct(
        public readonly string $name,
    ) {
    }

    public function add(Field $field): self
    {
        $this->fields[$field->name] = $field;

        return $this;
    }

    public function fields(): array
    {
        return $this->fields;
    }
}
