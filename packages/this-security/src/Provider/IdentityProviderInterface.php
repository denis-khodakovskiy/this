<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Security\Provider;

use This\Contracts\RequestContextInterface;
use This\Security\Identity\UserIdentity;

interface IdentityProviderInterface
{
    public function supports(RequestContextInterface $requestContext): bool;

    public function resolve(RequestContextInterface $requestContext): ?UserIdentity;
}
