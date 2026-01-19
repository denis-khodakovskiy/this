<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App\This\Core\Routing\Policies;

use This\Contracts\RequestMethodsEnum;
use This\Contracts\RouterPolicyInterface;

final readonly class RequestMethodPolicy implements RouterPolicyInterface
{
    /**
     * @param array<RequestMethodsEnum> $methods
     */
    public function __construct(
        public array $methods,
    ) {
    }
}
