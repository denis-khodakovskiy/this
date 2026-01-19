<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Validator\Validator;

use This\Validator\Schema\FormSchemaInterface;

interface ValidatorInterface
{
    public function validateInput(FormSchemaInterface $schema, array $input): ValidationResult;

    public function validate(object $object): ValidationResult;
}
