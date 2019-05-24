<?php
require_once    'vendor/autoload.php';

use App\App;
use App\Utils\Buffer;

//加载路由表
$app  = App::getInstance()->loadRouts();

$http = new Swoole\Http\Server("0.0.0.0", 8080);
$http->on('request', function ($request, $response) use ($app) {

    Buffer::clean();

    try {
        $action     = $app->matchRoute($request->server);
        $method     = $action['action'];
        $controller = call_user_func([$action['controller'], 'getInstance']);

        return  $controller->{$method}($response, $request);
    } catch (\Exception $e) {

        $response->status(500);

        return  $response->end($e->getMessage());
    }
});
$http->start();
