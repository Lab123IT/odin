<?php
namespace Lab123\Odin\Libs\Utils;

class Mask
{

    const FONE = '(%d%d) %d%d%d%d-%d%d%d%d';

    const CEL = '(%d%d) %d%d%d%d-%d%d%d%d%d';

    const CPF = '%d%d%d.%d%d%d.%d%d%d-%d%d';

    const CNPJ = '%d%d.%d%d%d.%d%d%d/%d%d%d%d-%d%d';

    const CEP = '%d%d%d%d%d-%d%d%d';

    public static function format($mask, $str)
    {
        try {
            return vsprintf($mask, str_split($str));
        } catch (\Exception $e) {
            return '';
        }
    }
}
