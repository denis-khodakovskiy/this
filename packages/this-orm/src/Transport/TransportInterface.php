<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\Transport;

use This\ORM\DQL\Compiled\Query\CompiledDelete;
use This\ORM\DQL\Compiled\Query\CompiledInsert;
use This\ORM\DQL\Compiled\Query\CompiledSelect;
use This\ORM\DQL\Compiled\Query\CompiledUpdate;

interface TransportInterface
{
    public function prepareSelect(CompiledSelect $query): string;

    public function prepareInsert(CompiledInsert $query): string;

    public function prepareUpdate(CompiledUpdate $query): string;

    public function prepareDelete(CompiledDelete $query): string;
}
