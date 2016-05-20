<?php
namespace Lab123\Odin\Libs;

use App;

class Api
{

    /**
     * Url da API
     *
     * @return string
     */
    public static function url()
    {
        return env('API_URL', 'http://localhost/api');
    }

    /**
     * Return Id Decoded
     *
     * @return array
     */
    public static function decodeHashId($idHashed)
    {
        if (! config('odin.hashid.active')) {
            return $idHashed;
        }
        
        $hashids = App::make('Hashids');
        $hashId = $hashids->decode($idHashed);
        
        return (count($hashId) > 0) ? $hashId[0] : '';
    }

    /**
     * Return Id Encoded
     *
     * @return array
     */
    public static function encodeHashId($id)
    {
        if (! config('odin.hashid.active')) {
            return $id;
        }
        
        $hashids = App::make('Hashids');
        return $hashids->encode($id, date('d'));
    }
}