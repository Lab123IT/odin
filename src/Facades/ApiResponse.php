<?php
namespace Lab123\Odin\Facades;

use Illuminate\Support\Facades\Facade;

class ApiResponse extends Facade
{

    protected static function getFacadeAccessor()
    {
        return 'ApiResponse';
    }
}