<?php
namespace App\Types;

use App\Traits\Singleton;
use App\Builders\ObjectTypeBuilder;
use GraphQL\Type\Definition\ObjectType;

abstract class AbstractType
{
    use Singleton;

    /**
     * ObjectType对象
     */
    protected $object;

    /**
     * 获取字段列表
     *
     * @return mixed
     */
    abstract public function fields();

    /**
     * 静态获取ObjectType对象
     *
     * @return ObjectType
     */
    public static function getObject(): ObjectType
    {
        return static::getInstance()->fetch();
    }

    /**
     * 获取ObjectType对象
     *
     * @return ObjectType
     */
    public function fetch(): ObjectType
    {
        if (empty($this->object)) {

            $this->object   = $this->build();
        }

        return  $this->object;
    }

    /**
     * 生成ObjectType对象
     *
     * @return ObjectType
     */
    protected function build(): ObjectType
    {
        return  ObjectTypeBuilder::create()
            ->name($this->name())
            ->description($this->description())
            ->fields($this->fields())
            ->fetch();
    } 

    /**
     * 获取名称
     *
     * @return strnig
     */
    public function name(): string
    {
        return $this->_getStringAttribute('name', function () {

            $className  = get_called_class();
            $classSplit = explode('\\', $className);

            return      ucfirst(array_pop($classSplit));
        });
    }

    /**
     * 获取描述
     *
     * @return strnig
     */
    public function description(): string
    {
        return $this->_getStringAttribute('description', function () {

            $name       = $this->name();
            $article    = in_array($name[0], ['A','E','I','O','U']) ? 'an' : 'a';

            return      sprintf('%s %s Object', $article, $name);
        });
    }

    /**
     * 获取字符串值
     *
     * @param string
     * @param \Closure
     * @return string
     */
    protected function _getStringAttribute(string $attributeName, \Closure $default)
    {
        if (isset($this->$attributeName) && is_string($this->$attributeName)) {

            return $this->$attributeName;
        }

        return $default();
    }
}
