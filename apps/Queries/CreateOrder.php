<?php
namespace App\Queries;

use GraphQL\Type\Definition\Type;
use App\Types\Order;
use App\Types\OrderInput;
use GraphQL\Deferred;
use GraphQL\Type\Definition\ResolveInfo;
use App\App;
use App\Utils\Relay;

/**
 * Test
 */
class CreateOrder extends AbstractQuery
{
    public function args()
    {
        return  [
            'order' => [
                'type'          => OrderInput::getObject(),
                'description'   => '创建订单数据',
            ],
        ];
    }

    public function type()
    {
        return  Order::getObject();
    }

    public function resolve(): \Closure
    {
        return function ($current, $args, $context, ResolveInfo $info) {

            go(function () use ($args) {
                $saber      = App::getInstance()->getSaber('http://127.0.0.1:8080');
                $apiData    = $saber->post('/sim/order/create', $args)->getParsedJsonArray();
                $this->channel()->push($apiData);
            });

            return new Deferred(function () {

                $apiData    = $this->channel()->pop();

                return  array_merge($apiData['data']['order'], [
                    Relay::TYPE_FIELD   => $this->type(),
                ]);
            });
        };
    }
}
