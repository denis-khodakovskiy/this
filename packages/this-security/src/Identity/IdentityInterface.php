<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Security\Identity;

interface IdentityInterface
{
    public function getId(): string;

    public function isAuthenticated(): bool;

    public function getRoles(): array;

    public function getMeta(): array;
}
