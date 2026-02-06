<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App\Repository;

interface UserRepositoryInterface
{
    public function findById(int $id): mixed;
}
