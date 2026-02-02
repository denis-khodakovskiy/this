<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace This\ORM\Schema;

interface PersistableInterface extends SchemaTableInterface
{
    public function toArray(): array;
}
