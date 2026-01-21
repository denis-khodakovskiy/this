<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

namespace App\Forms;

use This\Validator\Constraint\Attributes\Email;
use This\Validator\Constraint\Attributes\Length;
use This\Validator\Constraint\Attributes\MinValue;
use This\Validator\Constraint\Attributes\Required;

class MyObject
{
    public function __construct(
        #[Required]
        #[Length(min: 3, max: 255)]
        public string $name,
        #[MinValue(min: 18)]
        public int $age,
        #[Required]
        #[Email]
        public string $email,
        #[Required]
        private ?string $password = null,
    ) {
    }
}
