<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App\Handlers\Dto;

final readonly class RequestId
{
    public function __construct(
        public mixed $id,
    ) {
    }
}
