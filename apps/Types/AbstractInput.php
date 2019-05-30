<?php
namespace App\Types;

use App\Traits\Singleton;
use App\Traits\TypeDescription;
use App\Traits\TypeName;
use App\Traits\HasDefaultAttribute;
use App\Builders\InputObjectTypeBuilder;
use GraphQL\Type\Definition\InputObjectType;

/**
 * 
 */
abstract class AbstractInput
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
     * 静态获取InputObjectType对象
     *
     * @return InputObjectType
     */
    public static function getObject(): InputObjectType
    {
        return static::getInstance()->fetch();
    }

    /**
     * 获取InputObjectType对象
     *
     * @return InputObjectType
     */
    public function fetch(): InputObjectType
    {
        if (empty($this->object)) {

            $this->object   = $this->build();
        }

        return  $this->object;
    }

    /**
     * 生成InputObjectType对象
     *
     * @return InputObjectType
     */
    protected function build(): InputObjectType
    {
        $builder    = InputObjectTypeBuilder::getInstance()
            ->name($this->name())
            ->description($this->description())
            ->fields($this->fields());

        return  $builder->build();
    }
}
