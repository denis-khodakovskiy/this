<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\Compiler;

use This\ORM\DQL\AST\JoinExpression;
use This\ORM\DQL\AST\RawExpression;
use This\ORM\DQL\AST\SetExpression;
use This\ORM\DQL\Compiled\Expression\CompiledInsertRowExpression;
use This\ORM\DQL\Compiled\Expression\CompiledJoinExpression;
use This\ORM\DQL\Compiled\Query\AbstractCompiledQuery;
use This\ORM\DQL\Compiled\Query\CompiledDelete;
use This\ORM\DQL\Compiled\Query\CompiledInsert;
use This\ORM\DQL\Compiled\Query\CompiledSelect;
use This\ORM\DQL\Compiled\Query\CompiledUpdate;
use This\ORM\DQL\Compiled\Token\ParamToken;
use This\ORM\DQL\Compiled\Token\RawToken;
use This\ORM\Query\AbstractQuery;
use This\ORM\Query\Delete;
use This\ORM\Query\Insert;
use This\ORM\Query\Select;
use This\ORM\Query\Update;
use This\ORM\Schema\SchemaTableInterface;

final readonly class QueryCompiler
{
    public function __construct(
        private ExpressionCompiler $expressionCompiler,
    ) {
    }

    public function compile(AbstractQuery $query): AbstractCompiledQuery
    {
        return match (true) {
            $query instanceof Insert => $this->compileInsert($query),
            $query instanceof Update => $this->compileUpdate($query),
            $query instanceof Delete => $this->compileDelete($query),
            $query instanceof Select => $this->compileSelect($query),
            default => throw new \RuntimeException(sprintf('Unknown query type: %s', get_class($query))),
        };
    }

    private function compileInsert(Insert $query): AbstractCompiledQuery
    {
        $values = [];
        $rows = $query->getRows();
        $parametersBag = new ParametersBag();

        if (!is_array(reset($rows))) {
            $rows = [$rows];
        }

        $columns = array_keys($rows[0]);

        foreach ($rows as $row) {
            $rowValues = [];

            foreach ($columns as $column) {
                $value = $row[$column] ?? throw new \LogicException(sprintf('Unknown column: %s', $column));

                $rowValues[] = $value instanceof RawExpression
                    ? new RawToken($value->expression)
                    : new ParamToken($parametersBag->next($value));
            }

            $values[] = new CompiledInsertRowExpression(values: $rowValues);
        }

        $schemaClass = $query->getSchemaClass();
        assert(is_subclass_of($schemaClass, SchemaTableInterface::class));

        return new CompiledInsert(
            table: $schemaClass::getTableName(),
            columns: $columns,
            rows: $values,
            params: $parametersBag->getParameters(),
        );
    }

    private function compileSelect(Select $query): AbstractCompiledQuery
    {
        $schemaClass = $query->getSchemaClass();
        assert(is_subclass_of($schemaClass, SchemaTableInterface::class));

        $parametersBag = new ParametersBag();

        return new CompiledSelect(
            fromTable: $schemaClass::getTableName(),
            alias: $query->getAlias() ?? 'awesomeTableAlias',
            select: $query->getSelect(),
            joins: $query->getJoins() !== []
                ? array_map(
                    callback: function (JoinExpression $join) use ($parametersBag) {
                        $schemaClass = $join->schemaClass;
                        assert(is_subclass_of($schemaClass, SchemaTableInterface::class));

                        return new CompiledJoinExpression(
                            tableName: $schemaClass::getTableName(),
                            alias: $join->alias,
                            on: $this->expressionCompiler->compile($join->on, $parametersBag),
                            type: $join->type->value,
                        );
                    },
                    array: $query->getJoins(),
                )
                : [],
            where: $query->getWhere() !== null
                ? $this->expressionCompiler->compile($query->getWhere(), $parametersBag)
                : null,
            having: $query->getHaving() !== null
                ? $this->expressionCompiler->compile($query->getHaving(), $parametersBag)
                : null,
            groupBy: $query->getGroupBy(),
            orderBy: $query->getOrderBy(),
            limit: $query->getLimit(),
            offset: $query->getOffset(),
            params: $parametersBag->getParameters(),
        );
    }

    private function compileUpdate(Update $query): AbstractCompiledQuery
    {
        $schemaClass = $query->getSchemaClass();
        assert(is_subclass_of($schemaClass, SchemaTableInterface::class));

        $parametersBag = new ParametersBag();

        return new CompiledUpdate(
            table: $schemaClass::getTableName(),
            alias: $query->getAlias() ?? 'awesomeTableAlias',
            joins: $query->getJoins() !== []
                ? array_map(
                    callback: function (JoinExpression $join) use ($parametersBag) {
                        $schemaClass = $join->schemaClass;
                        assert(is_subclass_of($schemaClass, SchemaTableInterface::class));

                        return new CompiledJoinExpression(
                            tableName: $schemaClass::getTableName(),
                            alias: $join->alias,
                            on: $this->expressionCompiler->compile($join->on, $parametersBag),
                            type: $join->type->value,
                        );
                    },
                    array: $query->getJoins(),
                )
                : [],
            set: array_map(
                function (SetExpression $setExpression) use ($parametersBag) {
                    return $this->expressionCompiler->compile($setExpression, $parametersBag);
                },
                $query->getSet(),
            ),
            where: $query->getWhere() !== null
                ? $this->expressionCompiler->compile($query->getWhere(), $parametersBag)
                : null,
            params: $parametersBag->getParameters(),
            all: $query->getAll(),
        );
    }

    private function compileDelete(Delete $query): AbstractCompiledQuery
    {
        $schemaClass = $query->getSchemaClass();
        assert(is_subclass_of($schemaClass, SchemaTableInterface::class));

        $parametersBag = new ParametersBag();

        return new CompiledDelete(
            table: $schemaClass::getTableName(),
            alias: $query->getAlias() ?? 'awesomeTableAlias',
            joins: $query->getJoins() !== []
                ? array_map(
                    callback: function (JoinExpression $join) use ($parametersBag) {
                        $schemaClass = $join->schemaClass;
                        assert(is_subclass_of($schemaClass, SchemaTableInterface::class));

                        return new CompiledJoinExpression(
                            tableName: $schemaClass::getTableName(),
                            alias: $join->alias,
                            on: $this->expressionCompiler->compile($join->on, $parametersBag),
                            type: $join->type->value,
                        );
                    },
                    array: $query->getJoins(),
                )
                : [],
            where: $query->getWhere() !== null
                ? $this->expressionCompiler->compile($query->getWhere(), $parametersBag)
                : null,
            params: $parametersBag->getParameters(),
            all: $query->getAll(),
        );
    }
}
