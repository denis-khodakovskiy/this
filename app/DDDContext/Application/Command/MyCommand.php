<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App\DDDContext\Application\Command;

final readonly class MyCommand
{
    public function __construct(
        public string $name,
        public int $age,
        public int $height,
        public int $weight,
        public string $color,
        public string $gender,
    ) {
    }
}
