<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Validator\Validator;

use This\Validator\Schema\FormSchemaInterface;

interface ValidatorInterface
{
    public function validate(FormSchemaInterface $schema, array $input): ValidationResult;
}
