<?php
namespace App\Controllers;

use GraphQL\GraphQL;
use GraphQL\Type\Schema;
use App\Types\Query;
use App\Types\Mutation;
use Swoole\Http\Response;
use Swoole\Http\Request;

class GraphQLController extends AbstractController
{
    /**
     * 获取状态
     */
    public function status (Response $response)
    {
        $response->header('Content-Type', 'text/html; charset=UTF-8');

        return $response->end('<h1>GraphQL Server is running</h1>');
    }

    /**
     * GraphQL服务
     */
    public function exec (Response $response, Request $request)
    {
        $schema         = new Schema([
            'query'     => Query::getObject(),
            'mutation'  => Mutation::getObject(),
        ]);
        $rawInput       = $request->rawContent();
        $input          = json_decode($rawInput, true);
        $operationName  = isset($input['operationName'])
                        ? $input['operationName']
                        : null;
        $query          = $input['query'];
        $variableValues = isset($input['variables']) ? $input['variables'] : null;
        $rootValue      = [];

        try {
            $result     = GraphQL::executeQuery($schema, $query, $rootValue, null, $variableValues, $operationName);
            $output     = $result->toArray();
        } catch (\Exception $e) {
            $output     = [
                'errors'    => [
                    'message'   => $e->getMessage(),
                ],
            ];
        }

        $response->header('Content-Type', 'application/json');

        return  $response->end(json_encode($output));
    }
}
