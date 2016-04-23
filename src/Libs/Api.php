<?php
namespace Lab123\Odin\Libs;

use Illuminate\Support\Facades\App;

class Api
{

    /**
     * Url da API
     *
     * @return string
     */
    public static function url()
    {
        return env('APP_URL', 'http://localhost');
    }
}