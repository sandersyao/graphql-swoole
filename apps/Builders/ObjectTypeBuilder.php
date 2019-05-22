<?php
namespace App\Builders;

use GraphQL\Type\Definition\ObjectType;

/**
 * 
 */
class ObjectTypeBuilder
{
    protected   $_optionItem    = [];
    protected   $_options       = [];

    public static function create (array $options = [])
    {
        return  new self($options);
    }

    /**
     * 
     */
    public function __construct(array $options = [])
    {
        $this->_optionItem  = [
            'name', 'description', 'fields', 'args', 'resolve', 'resolveField', 'astNode', 'extensionASTNodes'
        ];
        $this->_options     = $options;
    }

    public function fetch () {

        return new ObjectType($this->_options);
    }

    public function __call (string $method, array $args)
    {
        if (!in_array($method, $this->_optionItem)) {

            throw new \BadMethodCallException('method ' . $method . ' of ' . get_called_class() . ' not exists');
        }

        if (count($args) === 0) {

            return  $this->_options[$method];
        }

        $this->_options[$method]    = current($args);

        return  $this;
    }
}
