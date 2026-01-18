<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App\This\Core\Kernel;

use This\Contracts\ContainerInterface;
use This\Contracts\ContextInterface;
use This\Contracts\RequestInterface;
use This\Contracts\RouteInterface;

final class Context implements ContextInterface
{
    public function __construct(
        private readonly ContainerInterface $container,
        private ?RequestInterface $request = null,
        private mixed $response = null,
        private ?\Throwable $exception = null,
        private array $meta = [],
        private bool $isCli = false,
        private bool $isHttp = false,
        private ?RouteInterface $route = null,
    ) {
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    public function setRequest(RequestInterface $request): self
    {
        $this->request = $request;

        return $this;
    }

    public function getRequest(): RequestInterface
    {
        return $this->request;
    }

    public function setResponse(mixed $response): self
    {
        $this->response = $response;

        return $this;
    }

    public function getResponse(): mixed
    {
        return $this->response;
    }

    public function setException(\Throwable $exception): self
    {
        $this->exception = $exception;

        return $this;
    }

    public function getException(): ?\Throwable
    {
        return $this->exception;
    }

    public function addMeta(string $key, mixed $value): self
    {
        $this->meta[$key] = $value;

        return $this;
    }

    public function getMetaValue(string $key, mixed $default = null): mixed
    {
        return $this->meta[$key] ?? $default;
    }

    public function getMeta(): array
    {
        return $this->meta;
    }

    public function isCli(): bool
    {
        return $this->isCli;
    }

    public function setIsCli(bool $isCli): self
    {
        $this->isCli = $isCli;

        return $this;
    }

    public function isHttp(): bool
    {
        return $this->isHttp;
    }

    public function setIsHttp(bool $isHttp): self
    {
        $this->isHttp = $isHttp;

        return $this;
    }

    public function setRoute(RouteInterface $route): self
    {
        $this->route = $route;

        return $this;
    }

    public function getRoute(): ?RouteInterface
    {
        return $this->route;
    }
}
