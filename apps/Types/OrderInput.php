<?php
namespace App\Types;

use GraphQL\Type\Definition\Type;

/**
 * Test 订单输入
 */
class OrderInput extends AbstractInput
{
    public function fields()
    {
        return  function () {

            return  [
                [
                    'name'          => 'userId',
                    'description'   => '用户ID',
                    'type'          => Type::nonNull(Type::id()),
                ],
                [
                    'name'          => 'orderGoods',
                    'description'   => '订单商品信息',
                    'type'          => Type::nonNull(Type::listOf(OrderGoodsInput::getObject())),
                ],
            ];
        };
    }
}
