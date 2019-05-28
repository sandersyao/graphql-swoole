<?php
namespace App\Builders;

use App\Validators\Validator;

/**
 * 抽象构建器 
 */
abstract class AbstractBuilder
{
    /**
     * 配置数据
     */
    protected   $_options       = [];

    /**
     * 获取实例
     */
    public static function getInstance (...$args) {

        return new static(...$args);
    }

    /**
     * 获取实例
     *
     * @param   array $options
     */
    protected function __construct (array $options = []) {

        $this->_options = array_filter($options, function ($key) {
            return in_array($key, $this->getItems());
        }, ARRAY_FILTER_USE_KEY);
    }

    /**
     * 获取配置项列表
     *
     * @return  array
     */
    abstract public function getItems (): array;

    /**
     * 获取构建结果
     *
     * @param   mixed   $args[]
     * @return  mixed
     */
    abstract protected function fetch (...$args);

    /**
     * 获取当前配置数据
     *
     * @return  array
     */
    public function options(): array
    {
        return $this->_options;
    }

    public function build ()
    {
        Validator::assertArray($this->getItems(), $this->_options);

        return $this->fetch();
    }

    /**
     * 构建方法调用
     *
     * @param   string $method
     * @param   array  $args
     * @return  mixed
     */
    public function __call (string $method, array $args)
    {
        $itemsAllow = $this->getItems();

        if (!isset($itemsAllow[$method])) {

            throw new \BadMethodCallException('method ' . $method . ' of ' . get_called_class() . ' not exists');
        }

        // 当参数空的时候 获取当前配置项的值
        if (count($args) === 0) {

            return  $this->_options[$method];
        }

        $rule   = $itemsAllow[$method];
        $value  = current($args);
        Validator::assertRule($rule, $value);
        $this->_options[$method]    = $value;

        return  $this;
    }
}

