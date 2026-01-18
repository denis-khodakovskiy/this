<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App\Factories;

use App\This\Core\Routing\Policies\RouterRequestMethodPolicyResolver;
use App\This\Core\Routing\PolicyRegistry\RouterPolicyRegistry;

final readonly class RouterPolicyRegistryFactory
{
    public function __invoke(): RouterPolicyRegistry
    {
        return new RouterPolicyRegistry([
            new RouterRequestMethodPolicyResolver(),
        ]);
    }
}
