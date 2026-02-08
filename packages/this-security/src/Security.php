<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Security;

use This\Contracts\RequestMetaCollectorInterface;
use This\Security\Identity\UserIdentity;

final readonly class Security implements SecurityInterface
{
    public function __construct(
        private RequestMetaCollectorInterface $requestMeta,
    ) {
    }

    public function identity(): UserIdentity
    {
        return $this->requestMeta->get(UserIdentity::class);
    }

    public function isAuthenticated(): bool
    {
        return $this->identity()->isAuthenticated();
    }

    public function getUserId(): ?string
    {
        return $this->identity()->getUserId();
    }

    public function isGranted(string $role): bool
    {
        return in_array($role, $this->identity()->getRoles(), true);
    }
}
