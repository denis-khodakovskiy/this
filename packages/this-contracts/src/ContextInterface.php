<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Contracts;

interface ContextInterface
{
    public function getContainer(): ContainerInterface;

    public function setRequest(RequestInterface $request): self;

    public function getRequest(): RequestInterface;

    public function setResponse(mixed $response): self;

    public function getResponse(): mixed;

    public function setException(\Throwable $exception): self;

    public function getException(): ?\Throwable;

    public function addMeta(string $key, mixed $value): self;

    public function getMetaValue(string $key, mixed $default = null): mixed;

    public function getMeta(): array;

    public function isCli(): bool;

    public function setIsCli(bool $isCli): self;

    public function isHttp(): bool;

    public function setIsHttp(bool $isHttp): self;

    public function setRoute(RouteInterface $route): self;

    public function getRoute(): ?RouteInterface;
}
