<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Contracts;

interface RequestProviderInterface
{
    public function setRequest(RequestInterface $request): void;

    public function getRequest(): RequestInterface;
}
