<?php
namespace App\Types;

use GraphQL\Type\Definition\Type;

/**
 * Test 订单商品输入 
 */
class OrderGoodsInput extends AbstractInput
{
    public function fields()
    {
        return  function () {

            return  [
                [
                    'name'          => 'goodsId',
                    'description'   => '商品ID',
                    'type'          => Type::nonNull(Type::id()),
                ],
                [
                    'name'          => 'quantity',
                    'description'   => '商品数量',
                    'type'          => Type::nonNull(Type::float()),
                ],
            ];
        };
    }
}
