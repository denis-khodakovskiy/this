<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App\This\Core\Routing\PolicyRegistry;

use This\Contracts\RouterPolicyRegistryInterface;
use This\Contracts\RouterPolicyResolverInterface;
use This\Contracts\RouterPolicyInterface;

final readonly class RouterPolicyRegistry implements RouterPolicyRegistryInterface
{
    /**
     * @param array<RouterPolicyResolverInterface> $policyResolvers
     */
    public function __construct(
        public array $policyResolvers = [],
    ) {
    }

    public function getResolver(RouterPolicyInterface $policy): RouterPolicyResolverInterface
    {
        foreach ($this->policyResolvers as $resolver) {
            if ($resolver->supports($policy)) {
                return $resolver;
            }
        }

        throw new \RuntimeException("No resolver found for policy " . get_class($policy));
    }
}
