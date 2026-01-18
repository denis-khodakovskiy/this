<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App\This\Core\Request;

use This\Contracts\RequestInterface;
use This\Contracts\RequestProviderInterface;

final class RequestProvider implements RequestProviderInterface
{
    private ?RequestInterface $request = null;

    public function setRequest(RequestInterface $request): void
    {
        $this->request = $request;
    }

    public function getRequest(): RequestInterface
    {
        if ($this->request === null) {
            throw new \RuntimeException('Request not initialized yet');
        }

        return $this->request;
    }
}
