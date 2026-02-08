<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Security\Identity;

final readonly class UserIdentity implements IdentityInterface
{
    /**
     * @param array<array-key, non-empty-string> $roles
     * @param array<non-empty-string, mixed> $meta
     */
    public function __construct(
        private int|string|null $userId = null,
        private array $roles = [],
        private bool $authenticated = false,
        private ?string $provider = null,
        private ?string $impersonatorId = null,
        private array $meta = [],
    ) {
    }

    public function getId(): string
    {
        return 'user:' . $this->userId;
    }

    public function getUserId(): int|string
    {
        return $this->userId;
    }

    public function isAuthenticated(): bool
    {
        return $this->authenticated;
    }

    public function getRoles(): array
    {
        return $this->roles;
    }

    public function getProvider(): ?string
    {
        return $this->provider;
    }

    public function getImpersonatorId(): ?string
    {
        return $this->impersonatorId;
    }

    public function getMeta(): array
    {
        return $this->meta;
    }
}
