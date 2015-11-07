<?php
namespace Lab123\Odin\Libs\Utils;

class StringFormat
{

    public static function removeNumberMask($str)
    {
        return preg_replace('/[^0-9]/', '', $str);
    }
}