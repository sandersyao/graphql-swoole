<?php

namespace App\Types;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ResolveInfo;
use App\Utils\Relay;

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
                'name'      => 'id',
                'type'      => Type::nonNull(Type::id()),
                'resolve'   => function($value, $args, $context, ResolveInfo $info) {

                    return Relay::getGlobalId($info->parentType->name, $value[$info->fieldName]);
                }
            ],
        ];
    }

    public function resolveType(): \Closure
    {
        return function ($value, $context, ResolveInfo $info) {

            if (isset($value[Relay::TYPE_FIELD])) {

                return $value[Relay::TYPE_FIELD];
            }

            return  null;
        };
    }
}
