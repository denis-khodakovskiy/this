<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\Session\ID\Resolver;

use This\Session\ID\Generator\SessionIdGeneratorInterface;
use This\Session\ID\Persister\SessionIdPersisterInterface;

final readonly class CookieSessionIdResolver implements SessionIdResolverInterface
{
    public function __construct(
        private SessionIdGeneratorInterface $generator,
        private SessionIdPersisterInterface $persister,
    ) {
    }

    public function resolve(): string
    {
        $id = $this->generator->generate();
        $this->persister->persist($id);

        return $id;
    }

    public function clear(): void
    {
        $this->persister->clear();
    }
}
