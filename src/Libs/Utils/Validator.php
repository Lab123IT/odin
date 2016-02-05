<?php
namespace Lab123\Odin\Libs\Utils;

abstract class Validator
{

    public static function cpfOrCnpj($value)
    {
        return (strlen($value) == 11) ? self::cpf($value) : self::cnpj($value);
    }

    public static function cpf($value)
    {
        // Verifica se um número foi informado
        if (empty($value)) {
            return false;
        }
        
        $invalidCpfSequences = [
            '00000000000',
            '11111111111',
            '22222222222',
            '33333333333',
            '44444444444',
            '55555555555',
            '66666666666',
            '77777777777',
            '88888888888',
            '99999999999'
        ];
        
        // Elimina possivel mascara
        $cpf = preg_replace('/[^0-9]+/', '', $value);
        $cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);
        
        // Verifica se o numero de digitos informados é igual a 11
        if (strlen($cpf) != 11) {
            return false;
        }
        
        // Verifica se nenhuma das sequencias invalidas abaixo
        // foi digitada. Caso afirmativo, retorna falso
        if (in_array($cpf, $invalidCpfSequences)) {
            return false;
        }
        
        // Calcula os digitos verificadores para verificar se o
        // CPF é válido
        for ($t = 9; $t < 11; $t ++) {
            
            for ($d = 0, $c = 0; $c < $t; $c ++) {
                $d += $cpf{$c} * (($t + 1) - $c);
            }
            $d = ((10 * $d) % 11) % 10;
            if ($cpf{$c} != $d) {
                return false;
            }
        }
        
        return true;
    }

    public static function cnpj($value)
    {
        if (empty($value)) {
            return false;
        }
        
        // Deixa o CNPJ com apenas números
        $cnpj = preg_replace('/[^0-9]/', '', $value);
        
        // O valor original
        $originalCnpj = $cnpj;
        
        // Captura os primeiros 12 números do CNPJ
        $first_numbers = substr($cnpj, 0, 12);
        
        // Faz o primeiro cálculo
        $firstCalculation = $this->multiplyCnpj($first_numbers);
        
        // Se o resto da divisão entre o primeiro cálculo e 11 for menor que 2, o primeiro
        // Dígito é zero (0), caso contrário é 11 - o resto da divisívo entre o cálculo e 11
        $firstDigit = ($firstCalculation % 11) < 2 ? 0 : 11 - ($firstCalculation % 11);
        
        // Concatena o primeiro dígito nos 12 primeiros números do CNPJ
        // Agora temos 13 números aqui
        $first_numbers .= $firstDigit;
        
        // O segundo cálculo é a mesma coisa do primeiro, porém, começa na posição 6
        $secondCalculation = $this->multiplyCnpj($first_numbers, 6);
        $secondDigit = ($secondCalculation % 11) < 2 ? 0 : 11 - ($secondCalculation % 11);
        
        // Concatena o segundo dígito ao CNPJ
        $cnpj = $first_numbers . $secondDigit;
        
        // Verifica se o CNPJ gerado é identico ao enviado
        if ($cnpj === $originalCnpj) {
            return true;
        }
        
        return false;
    }
}