<?php
namespace App\Queries;

use GraphQL\Type\Definition\Type;
use App\Types\OrderGoods as OrderGoodsType;
use GraphQL\Deferred;
use GraphQL\Type\Definition\ResolveInfo;
use App\Utils\Buffer;
use App\App;

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

            Buffer::getInstance()->add('listGoodsByOrderId', $current['id']);

            return new Deferred(function () use ($current) {

                $listOrderGoods = Buffer::getInstance()->exec('listGoodsByOrderId', function ($listOrderId) {

                    go(function () use ($listOrderId) {

                        $apiData    = App::getInstance()->getSaber('http://127.0.0.1:8080')
                            ->post('/sim/orderGoods', [
                                'orderId' => $listOrderId
                            ])->getParsedJsonArray();
                        $this->channel()->push($apiData);
                    });

                    $apiData    = $this->channel()->pop();

                    return $apiData['data']['list'];
                });

                //return data from dataset by copied root value 
                return array_values(array_filter($listOrderGoods, function ($orderGoods) use ($current) {

                    return $current['id'] == $orderGoods['orderId'];
                }));
            });
        };
    }
}

