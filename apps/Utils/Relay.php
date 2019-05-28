<?php
namespace App\Utils;

use GraphQL\Type\Definition\ResolveInfo;

/**
 * 
 */
class Relay
{
    const SEPARATOR_TYPE_ID = ':';

    const NAMESPACE_QUERY   = '\\App\\Queries';

    const ID_FIELD          = 'id';

    const TYPE_FIELD        = '__type';

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
}
