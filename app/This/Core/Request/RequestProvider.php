<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App\This\Core\Request;

use This\Contracts\RequestInterface;
use This\Contracts\RequestMetaCollectorInterface;
use This\Contracts\RequestProviderInterface;

final readonly class RequestProvider implements RequestProviderInterface
{
    public function __construct(
        private RequestMetaCollectorInterface $requestMeta,
    ) {
    }

    public function getRequest(): RequestInterface
    {
        $request = $this->requestMeta->get(RequestInterface::class);

        if (!$request instanceof RequestInterface) {
            throw new \RuntimeException('Request not initialized yet');
        }

        return $request;
    }
}
