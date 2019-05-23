<?php
namespace App\Controllers;

class StaticController extends AbstractController
{
    public function read ($request, $response)
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
