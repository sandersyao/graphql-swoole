<?php
namespace App\Types;

use GraphQL\Type\Definition\Type;
use GraphQL\Deferred;
use App\Types\AbstractType;
use App\Queries\Orders;

/**
 * 根查询
 */
class Query extends AbstractType
{
    public function fields()
    {
        return  [
            Orders::fetchOptions(),
        ];
    }
}
