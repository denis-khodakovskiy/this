<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\Repository;

interface RepositoryContextInterface
{
    public function for(string $schemaClass, ?string $primaryKey = 'id'): self;

    public function find(mixed $id): mixed;
}
