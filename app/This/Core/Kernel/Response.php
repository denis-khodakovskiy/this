<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App\This\Core\Kernel;

final readonly class Response
{
    public function __construct(
        public int $statusCode = 200,
        public string $message = '',
        public array $headers = [],
        public array $cookies = [],
        public array $files = [],
    ) {
    }
}
