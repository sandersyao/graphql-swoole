<?php
namespace App\Validators\Rules;

use App\Traits\Singleton;
use App\Validators\NotExists;

/**
 * 
 */
class Required
{
    use Singleton;

    public function handler($value, array $args)
    {
        if ($value instanceof NotExists) {

            throw new \UnexpectedValueException('invalid');
        }
    }
}
