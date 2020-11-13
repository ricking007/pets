<?php

namespace App\libs;

use DateTime;

class Util {
    /*
     * Valida datas no formato dd/mm/YYYY
     */

    static function validaData($valor) {
        $data = explode("/", $valor);
        if (isset($data[0]) && isset($data[1]) && isset($data[2])) {
            if (checkdate($data[1], $data[0], $data[2])) {
                return true;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }
    /*
     * Valida valores de CNPJ. Pode recebe com mascara ou sem
     */
    function validar_cnpj($cnpj) {
        $cnpj = preg_replace('/[^0-9]/', '', (string) $cnpj);
        // Valida tamanho
        if (strlen($cnpj) != 14)
            return false;
        // Valida primeiro dígito verificador
        for ($i = 0, $j = 5, $soma = 0; $i < 12; $i++) {
            $soma += $cnpj{$i} * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
        $resto = $soma % 11;
        if ($cnpj{12} != ($resto < 2 ? 0 : 11 - $resto))
            return false;
        // Valida segundo dígito verificador
        for ($i = 0, $j = 6, $soma = 0; $i < 13; $i++) {
            $soma += $cnpj{$i} * $j;
            $j = ($j == 2) ? 9 : $j - 1;
        }
        $resto = $soma % 11;
        return $cnpj{13} == ($resto < 2 ? 0 : 11 - $resto);
    }

    /*
     * Valida valores de CPF. Pode recebe com mascara ou sem
     */
    static function validaCpf($cpf = null) {
        // Verifica se um número foi informado
        if (empty($cpf)) {
            return false;
        }
        // Elimina possivel mascara
        $cpf = preg_replace('[^0-9]', '', $cpf);
        $cpf = preg_replace("/\D+/", "", $cpf);
        $cpf = str_pad($cpf, 11, '0', STR_PAD_LEFT);
        // Verifica se o numero de digitos informados é igual a 11
        if (strlen($cpf) != 11) {
            return false;
        }
        // Verifica se nenhuma das sequências invalidas abaixo foi digitada. Caso afirmativo, retorna falso
        elseif ($cpf == '00000000000' || $cpf == '11111111111' || $cpf == '22222222222' || $cpf == '33333333333' || $cpf == '44444444444' ||
                $cpf == '55555555555' || $cpf == '66666666666' || $cpf == '77777777777' || $cpf == '88888888888' || $cpf == '99999999999') {
            return false;
        }
        // Calcula os digitos verificadores para verificar se o CPF é válido
        else {
            for ($t = 9; $t < 11; $t++) {
                for ($d = 0, $c = 0; $c < $t; $c++) {
                    $d += $cpf{$c} * (($t + 1) - $c);
                }
                $d = ((10 * $d) % 11) % 10;
                if ($cpf{$c} != $d) {
                    return false;
                }
            }
            return true;
        }
    }

    /*
     * Verifica se é um email valido
     */

    public static function validaEmail($email) {
        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        } else {
            return false;
        }
    }

    /*
     * Verifica se é um telefone valido
     */

    public static function validaTelefone($telefone) {
        if (sizeof($telefone) == 14) {
            if (!eregi("^\([0-9]{2}\) [0-9]{4}-[0-9]{4}$", $telefone)) {
                return false;
            } else {
                return true;
            }
        } elseif (sizeof($telefone) == 15) {
            if (!eregi("^\([0-9]{2}\) [0-9]{4}-[0-9]{5}$", $telefone)) {
                return false;
            } else {
                return true;
            }
        }
    }

    /*
     * Verifica se é um telefone valido
     */

    public static function validaTelefone2($telefone) {
        return preg_match("/^\([0-9]{2}\) [0-9]{4}-[0-9]{5}$/", $telefone);
    }

    /*
     * Verifica se é um valor monetário valido
     */

    public static function validaMonetario($monetario) {
        if (substr($monetario, 0, 2) == "R$") {
            $monetario = str_replace("R$", "", $monetario);
            $monetario = str_replace(",", "", $monetario);
            $monetario = str_replace(".", "", $monetario);
            if (is_numeric($monetario)) {
                return true;
            }
        }
        return false;
    }

    /*
     * Testa se é um MD5 válido, se for devolve o próprio hash, se não transforma em hash MD5
     */

    public static function isMD5($hash) {
        return preg_match('/^[a-f0-9]{32}$/', $hash) ? $hash : md5($hash);
    }

    /*
     * Cria mascaras em numeros e strings
     */

    public static function mask($val, $mask) {
        $maskared = '';
        $k = 0;
        for ($i = 0; $i <= strlen($mask) - 1; $i++) {
            if ($mask[$i] == '#') {
                if (isset($val[$k])) {
                    $maskared .= $val[$k++];
                }
            } else {
                if (isset($mask[$i])) {
                    $maskared .= $mask[$i];
                }
            }
        }
        return $maskared;
    }

    /*
     * Configura a data no formato Brasileiro dd/mm/YYYY
     */

    public static function configuraDataBR($dataUS) {
        $dataHora = explode(" ", $dataUS);
        if (isset($dataHora[0]) && isset($dataHora[1])) {
            $data = explode("-", $dataHora[0]);
            return $dataBR = $data[2] . "/" . $data[1] . "/" . $data[0] . " " . $dataHora[1];
        } else {
            return "";
        }
    }

    /*
     * Cria diretorios recursivos no sistema
     */

    public static function rmkdir($path, $mode = 0755) {
        $path = rtrim(preg_replace(array("/\\\\/", "/\/{2,}/"), "/", $path), "/");
        $e = explode("/", ltrim($path, "/"));
        if (substr($path, 0, 1) == "/") {
            $e[0] = "/" . $e[0];
        }
        $c = count($e);
        $cp = $e[0];
        for ($i = 1; $i < $c; $i++) {
            if (!is_dir($cp) && !@mkdir($cp, $mode)) {
                return false;
            }
            $cp .= "/" . $e[$i];
        }
        return @mkdir($path, $mode);
    }

    /*
     * Arredonda um determinado valor para multiplo de um número, sendo o resultado do arredondamento sempre um número maior
     */

    public static function arredondaMultiplo($numero, $multiplo) {
        return round(($numero + $multiplo / 2) / $multiplo) * $multiplo;
    }

    /*
     * Verifica se existe o valor em uma matriz, passando o valor, a matriz e o campo desejado
     */

    public static function in_multiarray(&$respostas, $questao, $regras, $field) {
        $top = sizeof($regras) - 1;
        $bottom = 0;
        $match = 0;
        while ($bottom <= $top) {
            if ($regras[$bottom][$field] == $questao) {
                $respostas[$match] = $regras[$bottom];
                $match++;
            }
            $bottom++;
        }
    }

    /*
     * Remove caracteres indesejados
     */

    public static function removeAcentosCaracteresEsp($str) {
        $badchars = array(")", "(", "'", "\"", ";", "--", "\\", ">", "..", "/", "!", "@", "#", "%", "¨", "&", "*", "_", ".", "&");
        $str = str_replace($badchars, '', $str);
        // Assume $str esteja em UTF-8
        $find = "áàãâéêíóôõúüçÁÀÃÂÉÊÍÓÔÕÚÜÇ";
        $replace = "aaaaeeiooouucAAAAEEIOOOUUC";
        $keys = array();
        $values = array();
        preg_match_all('/./u', $find, $keys);
        preg_match_all('/./u', $replace, $values);
        $mapping = array_combine($keys[0], $values[0]);
        return strtr($str, $mapping);
    }

    public static function regexRange($from, $to) {
        if ($from > 0 && $to > 0 && $from < $to) {
            $ranges = array($from);
            $increment = 1;
            $next = $from;
            $higher = true;
            while (true) {
                $next += $increment;
                if ($next + $increment > $to) {
                    if ($next <= $to) {
                        $ranges[] = $next;
                    }
                    $increment /= 10;
                    $higher = false;
                } elseif ($next % ($increment * 10) === 0) {
                    $ranges[] = $next;
                    $increment = $higher ? $increment * 10 : $increment / 10;
                }
                if (!$higher && $increment < 10) {
                    break;
                }
            }
            $ranges[] = $to + 1;
            $regex = '^(?:';
            for ($i = 0; $i < sizeof($ranges) - 1; $i++) {
                $str_from = (string) ($ranges[$i]);
                $str_to = (string) ($ranges[$i + 1] - 1);
                for ($j = 0; $j < strlen($str_from); $j++) {
                    if ($str_from[$j] == $str_to[$j]) {
                        $regex .= $str_from[$j];
                    } else {
                        $regex .= "[" . $str_from[$j] . "-" . $str_to[$j] . "]";
                    }
                }
                $regex .= "|";
            }
            return substr($regex, 0, strlen($regex) - 1) . ')$';
        } else {
            return "";
        }
    }

    public static function currentDateFull() {
        $day = date('d');
        $month = date('n');
        $year = date('Y');

        $months = array(1 => 'Janeiro', 2 => 'Fevereiro', 3 => 'Março', 4 => 'Abril', 5 => 'Maio',
            6 => 'Junho', 7 => 'Julho', 8 => 'Agosto', 9 => 'Setembro', 10 => 'Outrubro',
            11 => 'Novembro', 12 => 'Dezembro');
        return $day . ' de ' . $months[$month] . ' de ' . $year;
    }

    public static function calcularTempo($entrada, $saida) {

        $datatime1 = new DateTime($entrada);
        $datatime2 = new DateTime($saida);

        $data1 = $datatime1->format('Y-m-d H:i:s');
        $data2 = $datatime2->format('Y-m-d H:i:s');

        $diff = $datatime1->diff($datatime2);
        $minutos = ($diff->h * 60) + $diff->i;

        return $minutos;
    }

    public static function calcularMinutos($array) {

        if ($array[0] >= 1) {
            return true;
        } else if (!empty($array) && $array[1] <= 30) {
            return true;
        } else {
            return false;
        }
    }

    public static function convert_chars_to_entities($str) {
        $str = str_replace('À', '&#192;', $str);
        $str = str_replace('Á', '&#193;', $str);
        $str = str_replace('Â', '&#194;', $str);
        $str = str_replace('Ã', '&#195;', $str);
        $str = str_replace('Ä', '&#196;', $str);
        $str = str_replace('Å', '&#197;', $str);
        $str = str_replace('Æ', '&#198;', $str);
        $str = str_replace('Ç', '&#199;', $str);
        $str = str_replace('È', '&#200;', $str);
        $str = str_replace('É', '&#201;', $str);
        $str = str_replace('Ê', '&#202;', $str);
        $str = str_replace('Ë', '&#203;', $str);
        $str = str_replace('Ì', '&#204;', $str);
        $str = str_replace('Í', '&#205;', $str);
        $str = str_replace('Î', '&#206;', $str);
        $str = str_replace('Ï', '&#207;', $str);
        $str = str_replace('Ð', '&#208;', $str);
        $str = str_replace('Ñ', '&#209;', $str);
        $str = str_replace('Ò', '&#210;', $str);
        $str = str_replace('Ó', '&#211;', $str);
        $str = str_replace('Ô', '&#212;', $str);
        $str = str_replace('Õ', '&#213;', $str);
        $str = str_replace('Ö', '&#214;', $str);
        $str = str_replace('×', '&#215;', $str);  // Yeah, I know.  But otherwise the gap is confusing.  --Kris
        $str = str_replace('Ø', '&#216;', $str);
        $str = str_replace('Ù', '&#217;', $str);
        $str = str_replace('Ú', '&#218;', $str);
        $str = str_replace('Û', '&#219;', $str);
        $str = str_replace('Ü', '&#220;', $str);
        $str = str_replace('Ý', '&#221;', $str);
        $str = str_replace('Þ', '&#222;', $str);
        $str = str_replace('ß', '&#223;', $str);
        $str = str_replace('à', '&#224;', $str);
        $str = str_replace('á', '&#225;', $str);
        $str = str_replace('â', '&#226;', $str);
        $str = str_replace('ã', '&#227;', $str);
        $str = str_replace('ä', '&#228;', $str);
        $str = str_replace('å', '&#229;', $str);
        $str = str_replace('æ', '&#230;', $str);
        $str = str_replace('ç', '&#231;', $str);
        $str = str_replace('è', '&#232;', $str);
        $str = str_replace('é', '&#233;', $str);
        $str = str_replace('ê', '&#234;', $str);
        $str = str_replace('ë', '&#235;', $str);
        $str = str_replace('ì', '&#236;', $str);
        $str = str_replace('í', '&#237;', $str);
        $str = str_replace('î', '&#238;', $str);
        $str = str_replace('ï', '&#239;', $str);
        $str = str_replace('ð', '&#240;', $str);
        $str = str_replace('ñ', '&#241;', $str);
        $str = str_replace('ò', '&#242;', $str);
        $str = str_replace('ó', '&#243;', $str);
        $str = str_replace('ô', '&#244;', $str);
        $str = str_replace('õ', '&#245;', $str);
        $str = str_replace('ö', '&#246;', $str);
        $str = str_replace('÷', '&#247;', $str);  // Yeah, I know.  But otherwise the gap is confusing.  --Kris
        $str = str_replace('ø', '&#248;', $str);
        $str = str_replace('ù', '&#249;', $str);
        $str = str_replace('ú', '&#250;', $str);
        $str = str_replace('û', '&#251;', $str);
        $str = str_replace('ü', '&#252;', $str);
        $str = str_replace('ý', '&#253;', $str);
        $str = str_replace('þ', '&#254;', $str);
        $str = str_replace('ÿ', '&#255;', $str);

        return $str;
    }

    /*
     * Array com Estados Brasileiros
     */
    function uf($uf) {
        $estadosBrasileiros = array(
            'AC' => 'Acre',
            'AL' => 'Alagoas',
            'AP' => 'Amapá',
            'AM' => 'Amazonas',
            'BA' => 'Bahia',
            'CE' => 'Ceará',
            'DF' => 'Distrito Federal',
            'ES' => 'Espírito Santo',
            'GO' => 'Goiás',
            'MA' => 'Maranhão',
            'MT' => 'Mato Grosso',
            'MS' => 'Mato Grosso do Sul',
            'MG' => 'Minas Gerais',
            'PA' => 'Pará',
            'PB' => 'Paraíba',
            'PR' => 'Paraná',
            'PE' => 'Pernambuco',
            'PI' => 'Piauí',
            'RJ' => 'Rio de Janeiro',
            'RN' => 'Rio Grande do Norte',
            'RS' => 'Rio Grande do Sul',
            'RO' => 'Rondônia',
            'RR' => 'Roraima',
            'SC' => 'Santa Catarina',
            'SP' => 'São Paulo',
            'SE' => 'Sergipe',
            'TO' => 'Tocantins'
        );
        return $estadosBrasileiros[$uf];
    }

    // protected function formatValor($var) {
    //     return number_format(floor(($var * 100)) / 100, 2, ',', '.');
    // }


}
