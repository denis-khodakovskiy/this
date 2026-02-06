<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\Repository;

use This\ORM\DQL\Expr;
use This\ORM\ORM;
use This\ORM\Query\Select;

final readonly class RepositoryContext implements RepositoryContextInterface
{
    public function __construct(
        private ORM $orm,
        private ?string $schemaClass = null,
        private ?string $primaryKey = 'id',
    ) {
    }

    public function for(string $schemaClass, ?string $primaryKey = 'id'): self
    {
        return new self($this->orm, $schemaClass, $primaryKey);
    }

    public function find(mixed $id): array|null
    {
        $result = $this->orm
            ->query(Select::from($this->schemaClass)
                ->where(Expr::equal($this->primaryKey, $id))
            )
            ->execute()
        ;

        return $result[0] ?? null;
    }
}
