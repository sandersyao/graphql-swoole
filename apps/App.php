<?php
namespace App;

use GraphQL\GraphQL;
use GraphQL\Type\Schema;
use App\Traits\Singleton;
use App\Builders\ObjectTypeBuilder;
use App\Types\Query;

class App
{
    use Singleton;

    /**
     * Schema实例
     */
    protected   $_schema;

    /**
     * 加载Schema实例
     * 实际场景中是否会根据当前用户的授权加载不同的Schema 还没想好这样做可能会破坏单一图原则
     *
     * @return App
     */
    public function loadTypes ()
    {
        $this->_schema = new Schema([
            'query' => Query::getObject(),
        ]);

        return  $this;
    }

    /**
     * 执行
     *
     * @param string
     * @param mixed
     * @return mixed
     */
    public function run (string $query, $variableValues = null) {

        $rootValue  = [];

        return  GraphQL::executeQuery($this->_schema, $query, $rootValue, null, $variableValues);
    }
}
