<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM;

use PDO;
use This\Orm\Compiler\QueryCompiler;
use This\ORM\DQL\Compiled\Query\CompiledDelete;
use This\ORM\DQL\Compiled\Query\CompiledInsert;
use This\ORM\DQL\Compiled\Query\CompiledSelect;
use This\ORM\DQL\Compiled\Query\CompiledUpdate;
use This\ORM\Query\AbstractQuery;
use This\ORM\Query\Insert;
use This\ORM\Query\Select;
use This\ORM\Schema\PersistableInterface;
use This\ORM\Transport\TransportInterface;

final class ORM
{
    private ?AbstractQuery $query = null;

    private ?string $preparedStatement = null;

    private array $params = [];

    public function __construct(
        private readonly QueryCompiler $queryCompiler,
        private readonly TransportInterface $transport,
        private readonly PDO $pdo,
    ) {
    }

    public function query(AbstractQuery $query): self
    {
        $this->query = $query;

        return $this;
    }

    public function execute(): string|int|array|false
    {
        $compiledQuery = $this->queryCompiler->compile($this->query);

        $this->preparedStatement = match (true) {
            $compiledQuery instanceof CompiledSelect => $this->transport->prepareSelect($compiledQuery),
            $compiledQuery instanceof CompiledInsert => $this->transport->prepareInsert($compiledQuery),
            $compiledQuery instanceof CompiledUpdate => $this->transport->prepareUpdate($compiledQuery),
            $compiledQuery instanceof CompiledDelete => $this->transport->prepareDelete($compiledQuery),
            default => throw new \LogicException('Unknown query type'),
        };

        $this->params = $compiledQuery->params;
        $stmt = $this->pdo->prepare($this->preparedStatement);

        foreach ($compiledQuery->params as $name => $value) {
            $stmt->bindValue(
                ':' . $name,
                $value,
                is_int($value) ? PDO::PARAM_INT : PDO::PARAM_STR
            );
        }

        $stmt->execute();

        return match (true) {
            $this->query instanceof Select => $stmt->fetchAll(),
            $this->query instanceof Insert => $this->pdo->lastInsertId(),
            default => $stmt->rowCount(),
        };
    }

    public function first(): ?array
    {
        assert($this->query instanceof Select);

        $data = $this->execute();

        return $data ? reset($data) : null;
    }

    public function column(): ?array
    {
        assert($this->query instanceof Select);

        $data = $this->execute();

        if (!$data) {
            return null;
        }

        $keys = array_keys($data[0]);

        return array_column($data, reset($keys));
    }

    public function scalar(): mixed
    {
        assert($this->query instanceof Select);

        $data = $this->execute();

        if (!$data) {
            return null;
        }

        $keys = array_keys($data[0]);
        $firstRow = reset($data);

        return $firstRow[reset($keys)];
    }

    public function exists(): bool
    {
        return $this->first() !== null;
    }

    /**
     * @param array<PersistableInterface> $persistables
     */
    public function insert(array $persistables): mixed
    {
        $query = Insert::into(schemaClass: $persistables[0]::class)
            ->values(array_map(
                callback: static function (PersistableInterface $persistable) {
                    $data = $persistable->toArray();

                    if ($data === []) {
                        throw new \LogicException('Persistable returned empty data');
                    }

                    return $data;
                },
                array: $persistables,
            ));

        return $this->query($query)->execute();
    }

    public function debug(): array
    {
        return [
            $this->preparedStatement,
            $this->params,
        ];
    }
}
