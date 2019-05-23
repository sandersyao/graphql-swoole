<?php
/**
 * 路由表
 * test
 */
return [
    '/graphql' => [
        'method'        => 'POST',
        'constroller'   => 'GraphQLController@run',
    ]
];
