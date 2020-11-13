<?php

namespace Framework\core;

use Framework\autoLoader\AutoLoader;
use Framework\core\Database;
use PDO;
use Exception;

class Model {

    private $autoLoader;
    protected $db;
    protected $lastInsertId;

    public function __construct() {
        $this->autoLoader = AutoLoader::getAutoLoader();
        $this->db = $this->autoLoader->db;
        $this->db->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        $this->db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $this->db->setAttribute(PDO::MYSQL_ATTR_INIT_COMMAND, 'SET NAMES utf8');
        $this->db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false);
    }
    
    /*
     * Executa uma consulta que não requer um cursor de retorno
     * por exemplo, um insert, update, delete e etc
     */

    function query($sql, $params = NULL) {
        
        if ($params == NULL || is_array($params)) {
            $this->db->beginTransaction();
            $stm = $this->db->prepare($sql);
            $stm->execute($params);
            $this->lastInsertId = $this->db->lastInsertId();
            $this->db->commit();
            return $stm;
        } else {
            echo json_encode(array("success" => false, "message" => "Erro ao executar consulta, "
                    . "os parametros devem estar no formato de array."));
            exit;
        }
    }

    public function debugData($param) {
        echo'<pre>';
        print_r($param);
        echo'</pre>';
        exit;
    }

    protected function unpercase($text) {
        $encoding = 'UTF-8'; // ou ISO-8859-1...
        return mb_convert_case($this->filterVarString($text), MB_CASE_UPPER, $encoding);
    }

    protected function filterVarInt($var) {
        return filter_var($var, FILTER_SANITIZE_NUMBER_INT);
    }

    protected function filterVarString($var) {
        return filter_var($var, FILTER_SANITIZE_STRING);
    }

    protected function filterVarDate($var) {
        return preg_replace("([^0-9/])", "", $var);
    }

    protected function filterVarEmail($var) {
        return filter_var($var, FILTER_SANITIZE_EMAIL);
    }

    protected function filterVarNumber($var) {
        return preg_replace("([^0-9])", "", $var);
    }

    protected function filterVarFloat($var) {
        return filter_var($var, FILTER_SANITIZE_NUMBER_FLOAT, FILTER_FLAG_ALLOW_FRACTION);
    }

}
