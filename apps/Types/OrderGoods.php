<?php
namespace App\Types;

use GraphQL\Type\Definition\Type;

/**
 * Test
 */
class OrderGoods extends AbstractType
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
                'name'  => 'name',
                'type'  => Type::string(),
            ],
            [
                'name'  => 'quantity',
                'type'  => Type::float(),
            ],
            [
                'name'  => 'unit',
                'type'  => Type::string(),
            ],
        ];
    }
}
