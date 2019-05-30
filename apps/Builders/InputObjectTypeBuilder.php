<?php
namespace App\Builders;

use GraphQL\Type\Definition\InputObjectType;

/**
 * 输入类型构建器
 */
class InputObjectTypeBuilder extends AbstractBuilder
{
    /**
     * 获取配置项列表
     *
     * @return  array
     */
    public function getItems (): array
    {
        return  [
            'fields'        => 'required|type:array,callable',
            'name'          => 'type:string',
            'description'   => 'type:string',
        ];
    }

    /**
     * 获取构建结果
     *
     * @param   mixed   $args[]
     * @return  mixed
     */
    public function fetch (...$args)
    {
        return new InputObjectType($this->_options);
    }
}
