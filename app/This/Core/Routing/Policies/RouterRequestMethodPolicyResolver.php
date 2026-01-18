<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App\This\Core\Routing\Policies;

use This\Contracts\RequestInterface;
use This\Contracts\RouterPolicyResolverInterface;
use This\Contracts\RouterPolicyInterface;

final readonly class RouterRequestMethodPolicyResolver implements RouterPolicyResolverInterface
{
    public function supports(RouterPolicyInterface $policy): bool
    {
        return $policy instanceof RequestMethodPolicy;
    }

    public function resolve(RouterPolicyInterface $policy, RequestInterface $request): bool
    {
        assert($policy instanceof RequestMethodPolicy);

        return in_array($request->getMethod(), $policy->methods);
    }
}
