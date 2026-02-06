<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\Hydrator;

interface HydratorInterface
{
    public function hydrate(array $data, string $dtoClass): mixed;
}
