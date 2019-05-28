<?php
namespace App\Traits;

/**
 * 获取接口解析器
 */
trait InterfaceResolveType
{
    /**
     * 获取描述
     *
     * @return strnig
     */
    public function resolveType()
    {
        return $this->getDefaultAttribute('resolveType', function () {

            return  null;
        });
    }

    /**
     * 获取字符串值
     *
     * @param string
     * @param \Closure
     * @return string
     */
    abstract protected function getDefaultAttribute(string $attributeName, \Closure $default);
}
