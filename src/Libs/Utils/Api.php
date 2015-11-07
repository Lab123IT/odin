<?php
namespace Lab123\Odin\Libs\Utils;

use Illuminate\Support\Facades\App;

class Api
{

    private static $localUrl = 'http://api.faseobra.dev/';

    private static $homUrl = 'http://api-faseobra.lab123.com.br/';

    private static $prodUrl = 'http://api.faseobra.com.br/';

    /**
     *
     * @return string - Url da API
     */
    public static function url()
    {
        switch (App::environment()) {
            case 'local':
                return self::$localUrl;
            case 'homologacao':
                return self::$homUrl;
            case 'production':
                return self::$prodUrl;
        }
    }
}
