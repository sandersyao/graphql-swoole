<?php
namespace App\Types;

use App\Traits\Singleton;
use App\Traits\TypeDescription;
use App\Traits\TypeName;
use App\Traits\HasDefaultAttribute;
use App\Builders\ObjectTypeBuilder;
use GraphQL\Type\Definition\ObjectType;

abstract class AbstractType
{
    use Singleton, HasDefaultAttribute, TypeDescription, TypeName;

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
        $builder    = ObjectTypeBuilder::getInstance()
            ->name($this->name())
            ->description($this->description())
            ->fields($this->fields());

        if (is_callable([$this, 'interfaces'])) {

            $builder->interfaces($this->interfaces());
        }

        return  $builder->build();
    } 
}
