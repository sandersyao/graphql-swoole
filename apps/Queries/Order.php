<?php
namespace App\Queries;

use App\Traits\IsNodeQuery;
use App\Types\Order as OrderType;
use App\Types\NodeInterface;
use GraphQL\Type\Definition\ResolveInfo;
use GraphQL\Deferred;
use App\App;
use App\Utils\Relay;

/**
 * 
 */
class Order extends AbstractQuery
{
    use IsNodeQuery;

    public function type()
    {
        return  OrderType::getObject();
    }

    public function resolve(): \Closure
    {
        return function ($current, $args, $context, ResolveInfo $info) {

            go(function () use ($args) {
                $saber      = App::getInstance()->getSaber('http://127.0.0.1:8080');
                $apiData    = $saber->post('/sim/order', $args)->getParsedJsonArray();
                $this->channel()->push($apiData);
            });
            $me     = $this;

            return new Deferred(function () use ($me) {

                $apiData    = $me->channel()->pop();

                return  array_merge($apiData['data']['order'], [
                    Relay::TYPE_FIELD   => $me->type(),
                ]);
            });
        };
    }
}
