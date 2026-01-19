<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Contracts;

interface RouteInterface
{
    public function getEnv(): RouteEnvEnum;

    public function isCli(): bool;

    public function isHttp(): bool;

    public function getName(): string;

    public function getPath(): string;

    public function getHandler(): string;

    public function getRequestFQCN(): ?string;

    /**
     * @return array<non-empty-string, mixed>
     */
    public function getRequirements(): array;

    /**
     * @return array<RouterPolicyInterface>
     */
    public function getPolicies(): array;

    /**
     * @return array<RequestMethodsEnum>
     */
    public function getMethods(): array;

    /**
     * @return array<mixed>
     */
    public function getMeta(): array;
}
