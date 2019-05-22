<?php
namespace App\Traits;

use Swoole\Lock;

/**
 * 单例
 */
trait Singleton
{
    /**
     * 获取实例
     *
     * @return mixed
     */
    public static function getInstance()
    {
        static $mapInstance = [];

        $className  = get_called_class();

        return  self::lock(function () use (& $mapInstance, $className) {

            if (!isset($mapInstance[$className])) {

                $mapInstance[$className]    = new $className;
            }

            return  $mapInstance[$className];
        });
    }

    /**
     * 锁
     *
     * @param \Closure $func
     * @return mixed
     */
    protected static function lock(\Closure $func)
    {
        $lock   = new Lock(SWOOLE_SPINLOCK);
        $lock->lock();

        try {

            $result = $func();
        } catch (Exception $e) {

            $lock->unlock();
            throw $e;
        }

        $lock->unlock();

        return  $result;
    }

    /**
     * 禁用外部创建实例
     */
    protected function __construct()
    {
        //禁止外部创建实例
    }

    /**
     * 禁止序列化
     *
     * @throws \LogicException
     */
    final public function __sleep()
    {
        throw new \LogicException('cannot serialize object from Singleton instance (class: ' . get_called_class() . ').');
    }

    /**
     * 禁止通过反序列化创建实例
     *
     * @throws \LogicException
     */
    final public function __wakeup()
    {
        throw new \LogicException('cannot unserialize object from Singleton instance (class: ' . get_called_class() . ').');
    }

    /**
     * 禁止克隆实例
     *
     * @throws \LogicException
     */
    final public function __clone()
    {
        throw new \LogicException('cannot clone object from Singleton instance (class: ' . get_called_class() . ')');
    }
}
