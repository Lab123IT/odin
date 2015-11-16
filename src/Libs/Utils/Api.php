<?php
namespace Lab123\Odin\Libs\Utils;

use Illuminate\Support\Facades\App;

class Api
{

    /**
     *
     * @return string - Url da API
     */
    public static function url()
    {
        return url() . '/';
    }
}