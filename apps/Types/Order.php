<?php
namespace App\Types;

use GraphQL\Type\Definition\Type;
use App\Queries\OrderGoods;

/**
 * Test
 */
class Order extends AbstractType
{

    public function fields()
    {
        return [
            [
                'name'  => 'id',
                'type'  => Type::id(),
            ],
            [
                'name'  => 'sn',
                'type'  => Type::string(),
            ],
            [
                'name'  => 'orderStatus',
                'type'  => Type::string(),
            ],
            OrderGoods::fetchOptions(),
        ];
    }
}
