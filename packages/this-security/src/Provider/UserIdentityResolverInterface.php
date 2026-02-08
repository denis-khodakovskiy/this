<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Security\Provider;

use This\Contracts\RequestContextInterface;
use This\Security\Identity\UserIdentity;

interface UserIdentityResolverInterface
{
    public function addProvider(IdentityProviderInterface $provider): void;

    public function resolveIdentity(RequestContextInterface $requestContext): ?UserIdentity;
}
