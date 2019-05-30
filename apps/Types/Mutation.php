<?php
namespace App\Types;

use App\Queries\CreateOrder;

/**
 * 
 */
class Mutation extends AbstractType
{
    public function fields()
    {
        return  [
            CreateOrder::fetchOptions(),
        ];
    }
}
