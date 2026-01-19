<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Validator\Validator;

final readonly class Violation
{
    public function __construct(
        public string $rule,
        public mixed $value,
        public array $params = [],
    ) {
    }
}
