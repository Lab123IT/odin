<?php
namespace Lab123\Odin\Libs;

use Illuminate\Support\Facades\App;

class Api
{

    /**
     *
     * @return string - Url da API
     */
    public static function url()
    {
        return env('APP_URL', 'http://localhost');
    }
}