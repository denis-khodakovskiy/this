<?php
/**
 * @author Denis Khodakovskii <denis.khodakovskiy@gmail.com>
 */

declare(strict_types=1);

namespace App\Schemas;

use This\ORM\Schema\PersistableInterface;
use This\ORM\Schema\SchemaTableInterface;

final readonly class OrderItemSchema implements SchemaTableInterface, PersistableInterface
{
    public function __construct(
        public int $orderId,
        public int $itemId,
        public int $price,
        public int $count,
    ) {
    }

    public static function getTableName(): string
    {
        return 'order_item';
    }

    public function toArray(): array
    {
        return [
            'orderId' => $this->orderId,
            'itemId' => $this->itemId,
            'price' => $this->price,
            'count' => $this->count,
        ];
    }
}