<?php

namespace App\models;

use Framework\core\Model;

class CorModel extends Model
{

    private $id_cor,
        $no_cor;

    function __construct()
    {
        parent::__construct();
    }

    function getCors()
    {
        $sql = "SELECT * FROM cor ORDER BY no_cor ASC";
        $res = $this->query($sql);
        return $res->fetchAll();
    }

    function getCor()
    {
        $sql = "SELECT * FROM cor WHERE id_cor = ?";
        $params = array($this->getId_cor());
        $res = $this->query($sql, $params);
        return $res->fetch();
    }

    function set()
    {
        if ($this->getId_cor()) {
            $sql = "UPDATE cor SET no_cor = ? WHERE id_cor = ?";
            $params = array($this->getNo_cor(), $this->getId_cor());
            $res = $this->query($sql, $params);
            $res->rowCount();
            return $this->getId_cor();
        } else {
            $sql = "INSERT INTO cor (no_cor) "
                . "VALUES (?);";
            $params = array($this->getNo_cor());
            $this->query($sql, $params);
            return $this->lastInsertId;
        }
    }

    function getCorByName()
    {
        $sql = "SELECT id_cor FROM cor WHERE no_cor = ?";
        $params = array($this->getNo_cor());
        $res = $this->query($sql, $params);
        return $res->fetch();
    }

    function delete()
    {
        $sql = "DELETE FROM cor WHERE id_cor = ?";
        $params = array($this->getId_cor());
        $res = $this->query($sql, $params);
        return $res->rowCount();
    }


    public function getId_cor()
    {
        return $this->id_cor;
    }

    public function setId_cor($id_cor)
    {
        $this->id_cor = $this->filterVarInt($id_cor);
    }

    public function getNo_cor()
    {
        return $this->no_cor;
    }

    public function setNo_cor($no_cor)
    {
        $this->no_cor = $this->filterVarString($no_cor);
    }
}
