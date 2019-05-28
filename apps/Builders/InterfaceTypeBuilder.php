<?php
namespace App\Builders;

use GraphQL\Type\Definition\InterfaceType;

/**
 * 
 */
class InterfaceTypeBuilder extends AbstractBuilder
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
            'resolveType'   => 'type:callable',
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
        return new InterfaceType($this->_options);
    }
}
