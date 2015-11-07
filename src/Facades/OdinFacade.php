<?php
namespace Lab123\Odin;

use Illuminate\Support\Facades\Facade;

class OdinFacade extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'lab123-odin';
    }
}