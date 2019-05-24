<?php
namespace App\Controllers;

use Swoole\Http\Response;
use Swoole\Http\Request;

class StaticController extends AbstractController
{
    /**
     * 静态资源访问
     */
    public function read (Response $response, Request $request)
    {
        $baseDir    = dirname(__DIR__) . '/../public';
        $filePath   = $baseDir . '/' . str_replace('..', '', $request->server['request_uri']);

        if (is_file($filePath)) {

            $mimeType   = mime_content_type($filePath);
            $response->header('Content-Type', $mimeType);

            return  $response->sendfile($filePath);
        }

        $response->status(404);

        return  $response->end();
    }
}
