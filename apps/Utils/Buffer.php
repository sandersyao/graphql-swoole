<?php
namespace App\Utils;

use App\Traits\Singleton;

/**
 * 
 */
class Buffer
{
    use Singleton;

    protected $map      = [];

    protected $result   = [];

    public function add (string $key, $value): Buffer
    {
        if (!isset($this->map[$key])) {

            $this->map[$key]    = [];
        }

        $this->map[$key][]  = $value;
        return $this;
    }

    public function exec (string $key, \Closure $callback)
    {
        if (!isset($this->result[$key])) {

            $this->result[$key] = $callback($this->map[$key]);
        }
        return  $this->result[$key];
    }

    public static function clean () {

        self::getInstance()->cleanAll();
    }

    public function cleanAll ()
    {
        $this->map      = [];
        $this->result   = [];
    }
}
