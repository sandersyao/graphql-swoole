<?php
namespace App\Types;

use GraphQL\Type\Definition\Type;

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
                'type'  => Type::string(),
            ],
            [
                'name'  => 'sn',
                'type'  => Type::string(),
            ],
            [
                'name'  => 'orderStatus',
                'type'  => Type::string(),
            ],
        ];
    }
}
