<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Contracts;

interface RequestResolverInterface
{
    public function supports(): bool;

    public function resolve(): RequestInterface;
}
