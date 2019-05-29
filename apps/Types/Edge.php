<?php
namespace App\Types;

use GraphQL\Type\Definition\Type;
use GraphQL\Type\Definition\ObjectType;

/**
 * 
 */
class Edge extends AbstractType
{
    const NAME_SUFFIX   = 'Edge';

    public $node;

    public static function getInstance (...$args) {

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

            return [
                [
                    'name'          => 'cursor',
                    'description'   => '游标',
                    'type'          => Type::nonNull(Type::string()),
                ],
                [
                    'name'          => 'node',
                    'description'   => '列表元素数据',
                    'type'          => Type::nonNull($this->node),
                ],
            ];
        };
    }
}
