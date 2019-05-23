<?php
namespace App\Queries;

use GraphQL\Type\Definition\Type;
use App\Types\OrderGoods as OrderGoodsType;
use GraphQL\Deferred;
use GraphQL\Type\Definition\ResolveInfo;

/**
 * Test
 */
class OrderGoods extends AbstractQuery
{
    public function type()
    {
        return Type::listOf(OrderGoodsType::getObject());
    }

    public function resovle(): \Closure
    {
        return function ($current, $args, $context, ResolveInfo $info) {

            //do something buffer

            return new Deferred(function () use ($current) {

                //load dataset by root values that in buffer
                //return data from dataset by copied root value 
                return [
                    [
                        'id'        => '123',
                        'sn'        => 'abc123',
                        'name'      => '2B铅笔',
                        'quantity'  => 4.0,
                        'unit'      => '件',
                    ],
                    [
                        'id'        => '124',
                        'sn'        => 'abc124',
                        'name'      => '花牛苹果',
                        'quantity'  => 0.125,
                        'unit'      => '公斤',
                    ],
                ];
            });
        };
    }
}

