<?php
namespace App\Validators\Rules;

use App\Traits\Singleton;
use App\Validators\NotExists;

/**
 * 类型校验
 */
class Type
{
    use Singleton;

    protected $mapTypeCallback;

    public function __construct()
    {
        $this->mapTypeCallback  = [
            'int'       => 'is_int',
            'float'     => 'is_float',
            'number'    => 'is_numeric',
            'string'    => 'is_string',
            'bool'      => 'is_bool',
            'array'     => 'is_array',
            'callable'  => 'is_callable',
        ];
    }

    public function handler($value, array $args)
    {
        if ($value instanceof NotExists) {

            return  ;
        }

        foreach ($args as $type) {

            if (!isset($this->mapTypeCallback[$type])) {

                throw new \UnexpectedValueException('Cannot assert type ' . $type . '.');
            }

            if (call_user_func($this->mapTypeCallback[$type], $value)) {

                return ;
            }
        }

        throw new \UnexpectedValueException('Unexpected value ' . get_class($value) . ' for type ' . implode(',', $args) . '.');
    }
}
