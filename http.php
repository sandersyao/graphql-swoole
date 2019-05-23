<?php
require_once    'vendor/autoload.php';

use App\App;

$app  = App::getInstance()->loadRouts();
$http = new Swoole\Http\Server("0.0.0.0", 8080);
$http->on('request', function ($request, $response) use ($app) {

    $action     = $app->matchRoute($request->server);
    $method     = $action['action'];
    $controller = call_user_func([$action['controller'], 'getInstance']);
    $controller->{$method}($request, $response);
});
$http->start();
