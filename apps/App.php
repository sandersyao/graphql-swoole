<?php
namespace App;

use App\Traits\Singleton;
use Swlib\Saber;
use Swlib\Http\ContentType;

/**
 * 大杂烩
 * 初期阶段先把有的没的放这里
 * 以后早晚得拆迁
 */
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

    /**
     * 获取Seber客户端实例
     *
     * @param  string $service
     * @return Saber
     */
    public function getSaber(string $service): Saber
    {
        static $map = [];

        if (!isset($map[$service]) || !($map[$service] instanceof Saber)) {

            $map[$service]  = Saber::create([
                'base_uri'  => $service,
                'headers'   => [
                    'Accept-Language'   => 'en,zh-CN;q=0.9,zh;q=0.8',
                    'Content-Type'      => ContentType::JSON,
                    'DNT'               => '1',
                    'User-Agent'        => null
                ],
                'use_pool'  => true,
            ]);
        }

        return  $map[$service];
    }
}
