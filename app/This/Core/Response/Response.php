<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App\This\Core\Response;

final readonly class Response
{
    public function __construct(
        public int $statusCode,
        public mixed $content,
        public array $headers = [],
    ) {
    }
}
