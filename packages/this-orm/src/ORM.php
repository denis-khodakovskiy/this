<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM;

use This\Orm\Compiler\QueryCompiler;
use This\ORM\DQL\Compiled\Query\CompiledDelete;
use This\ORM\DQL\Compiled\Query\CompiledInsert;
use This\ORM\DQL\Compiled\Query\CompiledSelect;
use This\ORM\DQL\Compiled\Query\CompiledUpdate;
use This\ORM\Query\AbstractQuery;
use This\ORM\Transport\TransportInterface;

final readonly class ORM
{
    public function __construct(
        private QueryCompiler $queryCompiler,
        private TransportInterface $transport,
    ) {
    }

    public function execute(AbstractQuery $query): mixed
    {
        $compiledQuery = $this->queryCompiler->compile($query);
        $sql = match (true) {
            $compiledQuery instanceof CompiledSelect => $this->transport->prepareSelect($compiledQuery),
            $compiledQuery instanceof CompiledInsert => $this->transport->prepareInsert($compiledQuery),
            $compiledQuery instanceof CompiledUpdate => $this->transport->prepareUpdate($compiledQuery),
            $compiledQuery instanceof CompiledDelete => $this->transport->prepareDelete($compiledQuery),
            default => throw new \LogicException('Unknown query type'),
        };

        print_r($compiledQuery->params);

        return $sql;
    }
}
