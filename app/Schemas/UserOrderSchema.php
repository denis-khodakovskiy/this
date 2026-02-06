<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App\Schemas;

use This\ORM\Schema\PersistableInterface;
use This\ORM\Schema\SchemaTableInterface;

final readonly class UserOrderSchema implements SchemaTableInterface, PersistableInterface
{
    public function __construct(
        public int $userId,
    ) {
    }

    public static function getTableName(): string
    {
        return 'user_orders';
    }

    public function toArray(): array
    {
        return [
            'userId' => $this->userId,
        ];
    }
}
