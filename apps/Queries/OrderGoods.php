<?php
namespace App\Queries;

use GraphQL\Type\Definition\Type;
use App\Types\OrderGoods as OrderGoodsType;
use GraphQL\Deferred;
use GraphQL\Type\Definition\ResolveInfo;
use App\Utils\Buffer;
use App\Utils\Relay;
use App\App;

/**
 * Test 订单商品查询
 */
class OrderGoods extends AbstractQuery
{
    public function type()
    {
        return  Relay::createConnection(OrderGoodsType::getObject());
    }

    public function args()
    {
        return Relay::mergeConnectionArgs();
    }

    public function resolve(): \Closure
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
                $list       = array_values(array_filter($listOrderGoods, function ($orderGoods) use ($current) {

                    return $current['id'] == $orderGoods['orderId'];
                }));
                $pageInfo   = [
                    'hasPreviousPage'   => false,
                    'hasNextPage'       => false,
                ];
                $edges      = [];

                foreach ($list as $offset => $item) {

                    $edges[]    = [
                        'node'      => $item,
                        'cursor'    => base64_encode($offset),
                    ];
                }

                return  [
                    'edges'     => $edges,
                    'pageInfo'  => $pageInfo,
                ];
            });
        };
    }
}

