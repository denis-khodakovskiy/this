<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App\This\Core\Request\Resolvers;

use This\Contracts\RequestResolverInterface;
use This\Contracts\RequestResolversRegistryInterface;

final readonly class RequestResolversRegistry implements RequestResolversRegistryInterface
{
    /**
     * @param array<array-key, RequestResolverInterface> $resolvers
     */
    public function __construct(
        private array $resolvers = [],
    ) {
    }

    public function getResolver(): RequestResolverInterface
    {
        foreach ($this->resolvers as $resolver) {
            if ($resolver->supports()) {
                return $resolver;
            }
        }

        throw new \RuntimeException('No request resolver found');
    }
}
