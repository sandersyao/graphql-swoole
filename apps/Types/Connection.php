<?php

namespace App\Types;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;

/**
 * Relay 
 */
class Connection extends AbstractType
{
    const NAME_SUFFIX   = 'Connection';

    protected   $node;

    public static function getInstance (...$args)
    {

        return new self(...$args);
    }

    protected function __construct (ObjectType $node)
    {
        $this->node = $node;
    }

    public function name(): string
    {
        return $this->node->name . self::NAME_SUFFIX;
    }

    /**
     * 
     */
    public function fields()
    {
        return function () {

            return  [
                [
                    'name'          => 'edges',
                    'description'   => '列表',
                    'type'          => Type::listOf(Edge::getInstance($this->node)->fetch()),
                ],
                [
                    'name'          => 'pageInfo',
                    'description'   => '分页信息',
                    'type'          => Type::nonNull(PageInfo::getObject()),
                ],
            ];
        };
    }
}
