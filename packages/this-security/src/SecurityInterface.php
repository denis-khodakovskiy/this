<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Security;

use This\Security\Identity\UserIdentity;

interface SecurityInterface
{
    public function identity(): ?UserIdentity;

    public function isAuthenticated(): bool;

    public function getUserId(): ?string;

    public function isGranted(string $role): bool;
}
