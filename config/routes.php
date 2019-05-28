<?php
/**
 * 路由表
 * test
 */
return [
    //GraphQL Service
    'POST /graphql'         => 'GraphQLController@exec',

    //Service Status check
    'GET /graphql'          => 'GraphQLController@status',

    //Simulations
    'POST /sim/order'       => 'SimulationApiController@orderById',
    'POST /sim/orders'      => 'SimulationApiController@ordersQuery',
    'POST /sim/orderGoods'  => 'SimulationApiController@orderGoodsQuery',
];
