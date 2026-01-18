<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App\Handlers\Dto;

final readonly class CreateUserRequestDto
{
    public function __construct(
        public string $email,
        public string $password,
        public ?int $age = null,
    ) {
    }
}
