<?php

namespace App\Types;

use GraphQL\Type\Definition\Type;

/**
 * node接口
 */
class NodeInterface extends AbstractInterface
{
    /**
     * fields
     */
    public function fields()
    {
        return [
            [
                'name'  => 'id',
                'type'  => Type::nonNull(Type::id()),
            ],
        ];
    }
}
