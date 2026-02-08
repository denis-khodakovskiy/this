<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Security\Provider;

use This\Contracts\RequestContextInterface;
use This\Security\Identity\UserIdentity;

final class UserIdentityResolver implements UserIdentityResolverInterface
{
    /** @var array<array-key, IdentityProviderInterface> */
    private array $providers = [];

    public function addProvider(IdentityProviderInterface $provider): void
    {
        if (!isset($this->providers[$provider::class])) {
            $this->providers[$provider::class] = $provider;
        }
    }

    public function resolveIdentity(RequestContextInterface $requestContext): UserIdentity
    {
        foreach ($this->providers as $provider) {
            if ($provider->supports($requestContext)) {
                $identity = $provider->resolve($requestContext);

                if ($identity !== null) {
                    return $identity;
                }
            }
        }

        return new UserIdentity();
    }
}
