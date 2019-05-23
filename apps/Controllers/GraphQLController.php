<?php
namespace App\Controllers;

use GraphQL\GraphQL;
use GraphQL\Type\Schema;
use App\Types\Query;

class GraphQLController extends AbstractController
{
    public function exec ($request, $response)
    {
        $schema     = new Schema([
            'query' => Query::getObject(),
        ]);
        $rawInput   = $request->rawContent();
        $input      = json_decode($rawInput, true);
        $query      = $input['query'];
        $variableValues = isset($input['variables']) ? $input['variables'] : null;
        $rootValue  = [];

        try {
            $result     = GraphQL::executeQuery($schema, $query, $rootValue, null, $variableValues);
            $output     = $result->toArray();
        } catch (\Exception $e) {
            $output     = [
                'errors'    => [
                    'message'   => $e->getMessage(),
                ],
            ];
        }

        $response->header('Content-Type', 'application/json');
        $response->end(json_encode($output));
    }
}
