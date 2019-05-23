<?php
namespace App;

use GraphQL\GraphQL;
use GraphQL\Type\Schema;
use App\Traits\Singleton;
use App\Types\Query;

class App
{
    use Singleton;

    /**
     * 路由表
     */
    protected   $_routes;

    /**
     * 加载路由
     */
    public function loadRouts ()
    {
        $this->_routes = require_once dirname(__DIR__) . '/config/routes.php';

        return  $this;
    }

    /**
     * 匹配路由
     *
     * @param array $server
     */
    public function matchRoute (array $server): array
    {
        $pattern    = $server['request_method'] . ' ' . rtrim($server['path_info'], '/');

        if (isset($this->_routes[$pattern])) {

            return  $this->resovleRoute($this->_routes[$pattern]);
        } 

        $pattern    = $server['request_method'] . ' ' . rtrim($server['request_uri'], '/');

        if (isset($this->_routes[$pattern])) {

            return  $this->resovleRoute($this->_routes[$pattern]);
        }

        return  $this->resovleRoute('StaticController@read');
    }

    /**
     * 解析路由
     *
     * @param string $routeValue
     */
    public function resovleRoute(string $routeValue)
    {
        [$controller, $action]  = explode('@', $routeValue);

        return [
            'controller'    => 'App\\Controllers\\' . $controller,
            'action'        => $action,
        ];
    }
}
