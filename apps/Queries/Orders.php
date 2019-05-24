<?php
namespace App\Queries;

use GraphQL\Type\Definition\Type;
use App\Types\Order;
use GraphQL\Deferred;
use GraphQL\Type\Definition\ResolveInfo;
use App\App;

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

            $saber      = App::getInstance()->getSaber('http://127.0.0.1:8080');
            $apiData    = $saber->post('/sim/orders', $args)->getParsedJsonArray();

            return  $apiData['data']['list'];
        };
    }
}
