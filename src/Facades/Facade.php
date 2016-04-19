<?php
namespace Lab123\Odin\Facades;

use Illuminate\Support\Facades\Facade;

class Facade extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'lab123-odin';
    }
}