<?php
namespace App\Utils;

use GraphQL\Type\Definition\ResolveInfo;
use App\Types\Connection;
use GraphQL\Type\Definition\Type;

/**
 * 
 */
class Relay
{
    const SEPARATOR_TYPE_ID             = ':';

    const NAMESPACE_QUERY               = '\\App\\Queries';

    const ID_FIELD                      = 'id';

    const TYPE_FIELD                    = '__type';

    const EDGE_TYPE_NAME_SUFFIX         = 'Edge';

    const CONNECTION_TYPE_NAME_SUFFIX   = 'Connection';

    public static function getGlobalId(string $typeName, string $id): string
    {
        return  base64_encode(base64_encode($typeName . self::SEPARATOR_TYPE_ID . $id));
    }

    public static function resolveGlobalId($value, $args, $context, ResolveInfo $info)
    {
        $idInfo         = self::fromGlobalId($args[self::ID_FIELD]);
        $queryClass     = self::getClass($idInfo['typeName']);
        $queryCallback  = [$queryClass, 'nodeResolve'];
 
        if (is_callable($queryCallback)) {

            $args[self::ID_FIELD]   = $idInfo[self::ID_FIELD];

            return  call_user_func($queryCallback, $value, $args, $context, $info);
        }

        return  null;
    }

    public static function getClass($typeName)
    {
        return self::NAMESPACE_QUERY . '\\' . ucfirst($typeName); 
    }

    public static function fromGlobalId(string $globalId)
    {
        list($typeName, $id) = explode(self::SEPARATOR_TYPE_ID, base64_decode(base64_decode($globalId)), 2);

        return [
            'typeName'  => $typeName,
            'id'        => $id,
        ];
    }

    public static function createConnection($nodeObject)
    {
        return  Connection::getInstance($nodeObject)->fetch();
    }

    public static function mergeConnectionArgs(array $args = [])
    {
        $argsConnection = [
            'first'     => [
                'name'          => 'first',
                'description'   => '从头获取条数',
                'type'          => Type::int(),
            ],
            'after'     => [
                'name'          => 'after',
                'description'   => '起始游标位置',
                'type'          => Type::string(),
            ],
            'last'      => [
                'name'          => 'last',
                'description'   => '从末尾获取条数',
                'type'          => Type::int(),
            ],
            'before'    => [
                'name'          => 'before',
                'description'   => '末尾游标位置',
                'type'          => Type::string(),
            ],
        ];

        foreach ($args as $name => $argument) {

            if (
                is_string($name)
                && isset($argsConnection[$name])
            ) {
                if ($argument['type'] != $argsConnection[$name]['type']) {
                    throw new \LogicException('Unexpect argument type for connection type'); 
                } else {

                    unset($argsConnection[$name]);
                }
            }
        }

        return  array_merge($args, $argsConnection);
    }
}
