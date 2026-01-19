<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App\This\Core\Routing;

use This\Contracts\RequestMethodsEnum;
use This\Contracts\RouteEnvEnum;
use This\Contracts\RouteInterface;
use This\Contracts\RouterPolicyInterface;

final readonly class Route implements RouteInterface
{
    /**
     * @param array<non-empty-string, RequestMethodsEnum> $methods
     * @param array<non-empty-string, mixed> $requirements
     * @param array<RouterPolicyInterface> $policies
     * @param array<mixed> $meta
     */
    public function __construct(
        private string $name,
        private string $path,
        private string $handler,
        private array $methods = [RequestMethodsEnum::GET],
        private RouteEnvEnum $env = RouteEnvEnum::HTTP,
        private ?string $requestFQCN = null,
        private array $requirements = [],
        private array $policies = [],
        private array $meta = [],
    ) {
    }

    public function getName(): string
    {
        return $this->name;
    }

    public function getPath(): string
    {
        return $this->path;
    }

    public function getHandler(): string
    {
        return $this->handler;
    }

    public function getMethods(): array
    {
        return $this->methods;
    }

    public function getEnv(): RouteEnvEnum
    {
        return $this->env;
    }

    public function isCli(): bool
    {
        return $this->env === RouteEnvEnum::CLI;
    }

    public function isHttp(): bool
    {
        return $this->env === RouteEnvEnum::HTTP;
    }

    public function getRequestFQCN(): ?string
    {
        return $this->requestFQCN;
    }

    public function getRequirements(): array
    {
        return $this->requirements;
    }

    public function getPolicies(): array
    {
        return $this->policies;
    }

    public function getMeta(): array
    {
        return $this->meta;
    }
}
