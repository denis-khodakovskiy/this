<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM;

use This\ORM\Query\AbstractQuery;
use This\ORM\Schema\PersistableInterface;

interface ORMInterface
{
    public function query(AbstractQuery $query): self;

    public function execute(): string|int|array|false;

    public function first(): ?array;

    public function column(): ?array;

    public function scalar(): mixed;

    public function exists(): bool;

    /**
     * @param array<PersistableInterface> $persistables
     */
    public function insert(array $persistables): mixed;

    public function rawSql(string $statement): array;
}
