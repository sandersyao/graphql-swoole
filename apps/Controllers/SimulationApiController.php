<?php
namespace App\Controllers;

use Swoole\Http\Response;
use Swoole\Http\Request;

/**
 * 模拟后端API
 */
class SimulationApiController extends AbstractController
{

    const CODE_DEFAULT      = 0;

    const MESSAGE_DEFAULT   = 'OK';

    /**
     * 创建订单
     */
    public function orderCreate (Response $response, Request $request)
    {
        //\Swoole\Coroutine::sleep(2);
        $query      = $this->in($request);
        var_dump($query);
        $listOrder  = $this->searchEqu('orders');
        $data       = [
            'order' => end($listOrder),
        ];
        return $this->out($response, $data);
    }

    /**
     * 订单查询
     */
    public function orderById (Response $response, Request $request)
    {
        //\Swoole\Coroutine::sleep(2);
        $query      = $this->in($request);
        $listOrder  = $this->searchEqu('orders', 'id', $query['id']);
        $data       = [
            'order' => current($listOrder),
        ];

        return  $this->out($response, $data);
    }

    /**
     * 订单查询
     */
    public function ordersQuery (Response $response, Request $request)
    {
        //\Swoole\Coroutine::sleep(2);
        $query      = $this->in($request);
        $listOrder  = 'All' == $query['orderStatus']
                    ? $this->searchEqu('orders')
                    : $this->searchEqu('orders', 'orderStatus', $query['orderStatus']);
        $data       = [
            'list'  => $listOrder,
            'count' => count($listOrder),
        ];

        return  $this->out($response, $data);
    }

    /**
     * 订单商品查询
     */
    public function orderGoodsQuery (Response $response, Request $request)
    {
        //\Swoole\Coroutine::sleep(3);
        $query          = $this->in($request);
        $listOrderGoods = is_array($query['orderId'])
                        ? $this->searchIn('ordergoods', 'orderId', $query['orderId'])
                        : $this->searchEqu('ordergoods', 'orderId', $query['orderId']);
        $data           = [
            'list'  => $listOrderGoods,
        ];

        return  $this->out($response, $data);
    }

    protected function in (Request $request)
    {
        return json_decode($request->rawContent(), true);
    }

    protected function out (Response $response, array $data, string $message = self::MESSAGE_DEFAULT, int $code = self::CODE_DEFAULT) {

        $structure  = [
            'code'      => $code,
            'message'   => $message,
            'data'      => $data,
        ];
        $response->header('Content-Type', 'application/json');

        return $response->end(json_encode($structure));
    }

    protected function searchIn (string $table, string $field, array $value): array
    {
        static $dataset = [];

        if (!isset($dataset[$table])) {

            $dataset[$table]    = $this->loadCsv($table);
        }

        return array_values(array_filter($dataset[$table], function ($row) use ($field, $value) {

            return  in_array($row[$field], $value);
        }));
    }

    protected function searchEqu (string $table, string $field = null, string $value = null): array
    {
        static $dataset = [];

        if (!isset($dataset[$table])) {

            $dataset[$table]    = $this->loadCsv($table);
        }

        if (empty($field)) {

            return  $dataset[$table];
        }

        return array_values(array_filter($dataset[$table], function ($row) use ($field, $value) {

            return  $row[$field] == $value;
        }));
    }

    protected function loadCsv ($table)
    {
        $result     = [];
        $csvFile    = dirname(__DIR__) . '/../config/' . $table . '.csv';

        if (!is_file($csvFile)) {

            return $result;
        }

        $lines      = file($csvFile);
        $head       = array_map('trim', explode(',', array_shift($lines)));

        foreach ($lines as $line) {

            $result[]   = array_combine($head, array_map('trim', explode(',', $line)));
        }

        return $result; 
    }
}
