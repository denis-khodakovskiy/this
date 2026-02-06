<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App\Repository;

use App\Schemas\UserSchema;
use This\ORM\Hydrator\HydratorInterface;
use This\ORM\Repository\RepositoryContextInterface;

final readonly class UserRepository implements UserRepositoryInterface
{
    public function __construct(
        private RepositoryContextInterface $repository,
        private HydratorInterface $hydrator,
    ) {
    }

    public function findById(int $id): ?UserSchema
    {
        $user = $this->repository->find($id);

        if (!$user) {
            return null;
        }

        return $this->hydrator->hydrate(
            $user,
            UserSchema::class,
        );
    }
}
