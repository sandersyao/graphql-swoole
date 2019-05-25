<?php

namespace App\Queries;

use App\Traits\Singleton;
use Swoole\Coroutine\Channel;

/**
 * 抽象查询
 *
 * 目前将查询(Query)看做特殊的字段(Field)，他拥有字段的全部属性
 * 查询和普通字段的区别在于查询必须带有resolve方法来获取数据
 * 通常情况下，获取列表(list)和对象(ObjectType)时经常会用到查询
 */
abstract class AbstractQuery
{
    use Singleton;

    const CHANNEL_SIZE  = 100;

    protected $options;

    protected $channel;

    protected function __construct()
    {
        $this->channel  = new Channel(self::CHANNEL_SIZE);
    }

    protected function channel()
    {
        return $this->channel;
    }

    /**
     * 获取类型
     */
    abstract public function type();

    /**
     * 获取resolve
     */
    abstract public function resovle(): \Closure;

    /**
     * 获取配置
     *
     * @return array
     */
    public static function fetchOptions(): array
    {
        return static::getInstance()->getOptions();
    }

    /**
     * 获取配置
     *
     * @return array
     */
    public function getOptions(): array
    {
        if (empty($this->options)) {

            $this->options  = [
                'name'          => $this->name(),
                'description'   => $this->description(),
                'type'          => $this->type(),
                'resolve'       => $this->resovle(),
            ];

            if (is_callable([$this, 'args'])) {

                $this->options['args'] = $this->args();
            }
        }

        return  $this->options;
    }

    /**
     * 获取名称
     * 默认取当前类型不包含命名空间的类名
     *
     * @return string
     */
    public function name(): string
    {
        return $this->_getStringAttribute('name', function () {

            $className  = get_called_class();
            $classSplit = explode('\\', $className);

            return  lcfirst(array_pop($classSplit));
        });
    }

    /**
     * 获取描述
     * 有瞎编的英文描述文案
     *
     * @return string
     */
    public function description(): string
    {
        return $this->_getStringAttribute('description', function () {

            $name       = $this->name();
            $article    = in_array($name[0], ['a','e','i','o','u']) ? 'an' : 'a';

            return      sprintf('%s %s Field', $article, $name);
        });
    }

    /**
     * 获取字符串属性
     *
     * @param string
     * @param \Closure
     * @return string
     */
    protected function _getStringAttribute(string $attributeName, \Closure $default): string
    {
        if (isset($this->$attributeName) && is_string($this->$attributeName)) {

            return $this->$attributeName;
        }

        return $default();
    }
}
