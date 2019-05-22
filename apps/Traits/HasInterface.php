<?php
namespace App\Traits;

use App\Contracts\HasInterface as HasInterfaceContract;

Trait HasInterface
{
    public function getInterfaces(): array
    {
        if (!isset($this->interfaces) && $this instanceof HasInterfaceContract) {

            throw \LogicException('attribute `interfaces` MUST be set on class ' . get_called_class() . '.');
        }

        if (!is_array($this->interfaces) && !is_callable($this->interfaces)) {

            throw \LogicException('attribute `interfaces` MUST be an array or a callable type on ' . get_called_class() . '.');
        }

        return $this->interfaces;
    }
}
