<?php
namespace App\Traits;

/**
 * 默认属性
 */
trait HasDefaultAttribute
{
    /**
     * 获取字符串值
     *
     * @param string
     * @param \Closure
     * @return string
     */
    protected function getDefaultAttribute(string $attributeName, \Closure $default)
    {
        if (isset($this->$attributeName) && is_string($this->$attributeName)) {

            return $this->$attributeName;
        }

        return $default();
    }
}
