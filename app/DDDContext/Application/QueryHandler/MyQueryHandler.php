<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App\DDDContext\Application\QueryHandler;

use App\DDDContext\Application\Query\MyQuery;

final class MyQueryHandler
{
    public function __invoke(MyQuery $query): int
    {
        return $query->id;
    }
}
