<?php
namespace App\Queries;

use App\Types\NodeInterface;
use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ResolveInfo;
use App\Utils\Relay;

/**
 * 
 */
class Node extends AbstractQuery
{
    public function args()
    {
        return  [
            'id'    => [
                'type'          => Type::nonNull(Type::id()),
                'description'   => 'global ID',
            ],
        ];
    }

    public function type()
    {
        return NodeInterface::getObject();
    }

    public function resolve(): \Closure
    {
        return function ($current, $args, $context, ResolveInfo $info) {

            return Relay::resolveGlobalId($current, $args, $context, $info);
        };
    }
}
