<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Contracts;

interface RouterPolicyResolverInterface
{
    public function supports(RouterPolicyInterface $policy): bool;

    public function resolve(RouterPolicyInterface $policy, RequestInterface $request): bool;
}
