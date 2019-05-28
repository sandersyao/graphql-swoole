<?php
namespace App\Types;

use GraphQL\Type\Definition\Type;
use App\Queries\OrderGoods;

/**
 * Test
 */
class Order extends AbstractType
{

    public function fields()
    {
        return  function () {

            return  NodeInterface::mergeFields([
                [
                    'name'  => 'sn',
                    'type'  => Type::string(),
                ],
                [
                    'name'  => 'orderStatus',
                    'type'  => Type::string(),
                ],
                OrderGoods::fetchOptions(),
            ]);
        };
    }

    public function interfaces()
    {
        return function () {

            return [
                NodeInterface::getObject(),
            ];
        };
    }
}
