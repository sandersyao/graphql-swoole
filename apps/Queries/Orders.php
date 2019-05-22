<?php
namespace App\Queries;

use GraphQL\Type\Definition\Type;
use App\Types\Order;
use GraphQL\Deferred;
use GraphQL\Type\Definition\ResolveInfo;

/**
 * Test
 */
class Orders extends AbstractQuery
{
    public function args()
    {
        return  [
            'orderStatus' => [
                'type'          => Type::string(),
                'description'   => 'F',
                'defaultValue'  => 'All',
            ],
        ];
    }

    public function type()
    {
        return Type::listOf(Order::getObject());
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
                        'id'            => '123',
                        'sn'            => 'abc123',
                        'orderStatus'   => 'paid',
                    ],
                    [
                        'id'            => '124',
                        'sn'            => 'abc124',
                        'orderStatus'   => 'paid',
                    ],
                ];
            });
        };
    }
}
