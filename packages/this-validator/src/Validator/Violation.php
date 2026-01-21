<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Validator\Validator;

final class Violation
{
    /**
     * @param array<string, mixed> $params
     * @param array<string, mixed> $meta
     */
    public function __construct(
        public readonly string $rule,
        public readonly mixed $value,
        public readonly array $params = [],
        public array $meta = [],
    ) {
    }
}
