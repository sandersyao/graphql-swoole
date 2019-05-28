<?php
namespace App\Traits;

use GraphQL\Type\Definition\ResolveInfo;

/**
 * Node查询
 */
trait IsNodeQuery
{
    abstract public function resolve(): \Closure;

    abstract static public function getInstance();

    static public function nodeResolve($value, $args, $context, ResolveInfo $info)
    {
        $callback   = static::getInstance()->resolve();

        return  $callback($value, $args, $context, $info);
    }

    public function fields ()
    {
        return Node::getInstance()->fields();
    }
}
