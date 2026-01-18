<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Contracts;

interface RouterPolicyRegistryInterface
{
    public function getResolver(RouterPolicyInterface $policy): RouterPolicyResolverInterface;
}