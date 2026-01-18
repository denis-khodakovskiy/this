<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App\Forms;

use This\Validator\Constraint\Length;
use This\Validator\Constraint\MinValue;
use This\Validator\FieldType\EmailType;
use This\Validator\FieldType\IntType;
use This\Validator\FieldType\StringType;
use This\Validator\Schema\Field;
use This\Validator\Schema\FormSchema;

class CreateUserFormSchema extends FormSchema
{
    public function __construct()
    {
        parent::__construct('user.create');

        $this->add(
            new Field(
                name: 'email',
                type: new EmailType(),
                required: true,
            ),
        );

        $this->add(
            new Field(
                name: 'password',
                type: new StringType(),
                required: true,
                constraints: [
                    new Length(
                        min: 8,
                    ),
                ],
            ),
        );

        $this->add(
            new Field(
                name: 'age',
                type: new IntType(),
                required: false,
                constraints: [
                    new MinValue(min: 18),
                ],
            ),
        );
    }
}
