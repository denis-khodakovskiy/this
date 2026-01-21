<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App\DDDContext\Application\Query;

final readonly class MyQuery
{
    public function __construct(
        public int $id,
    ) {
    }
}
