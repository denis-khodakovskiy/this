<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App\This\Core\Request;

use App\This\Core\Response\Response;
use This\Contracts\ContainerInterface;
use This\Contracts\RequestContextInterface;
use This\Contracts\RequestInterface;
use This\Contracts\RequestMetaCollectorInterface;
use This\Contracts\RouteInterface;

final class RequestContext implements RequestContextInterface
{
    public function __construct(
        private readonly ContainerInterface $container,
        private readonly RequestMetaCollectorInterface $meta,
        private mixed $response = null,
        private ?\Throwable $exception = null,
        private bool $isCli = false,
        private bool $isHttp = false,
        private ?RouteInterface $route = null,
    ) {
    }

    public function getContainer(): ContainerInterface
    {
        return $this->container;
    }

    public function getRequest(): RequestInterface
    {
        return $this->meta->get(RequestInterface::class);
    }

    public function setResponse(Response $response): self
    {
        $this->response = $response;

        return $this;
    }

    public function getResponse(): ?Response
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
        $this->meta->set($key, $value);

        return $this;
    }

    public function getMetaValue(string $key, mixed $default = null): mixed
    {
        return $this->meta->get($key, $default);
    }

    public function getMeta(): array
    {
        return $this->meta->all();
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
