<?php

namespace App\models;

use Framework\core\Model;

class TemplateModel extends Model
{

    private $id,
        $name;

    function __construct()
    {
        parent::__construct();
    }

    function getTemplates()
    {
        $sql = "SELECT * FROM tbTemplate ORDER BY name ASC";
        $res = $this->query($sql);
        return $res->fetchAll();
    }

    function getTemplate()
    {
        $sql = "SELECT * FROM tbTemplate WHERE id = ? ";
        $params = array($this->getId());
        $res = $this->query($sql, $params);
        return $res->fetch();
    }

    function set()
    {
        if ($this->getId()) {
            $sql = "UPDATE tbTemplate SET name = ? WHERE id = ?";
            $params = array($this->getName(), $this->getId());
            $res = $this->query($sql, $params);
            $res->rowCount();
            return $this->getId();
        } else {
            $sql = "INSERT INTO tbTemplate (name) "
                . "VALUES (?);";
            $params = array($this->getName());
            $this->query($sql, $params);
            return $this->lastInsertId;
        }
    }

    function getTemplateByName()
    {
        $sql = "SELECT id FROM tbTemplate WHERE name = ? ";
        $params = array($this->getName());
        $res = $this->query($sql, $params);
        return $res->fetch();
    }

    function delete()
    {
        $sql = "DELETE FROM tbTemplate WHERE Id = ?";
        $params = array($this->getId());
        $res = $this->query($sql, $params);
        return $res->rowCount();
    }


    /**
     * Get the value of id
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set the value of id
     *
     * @return  self
     */
    public function setId($id)
    {
        $this->id = $this->filterVarInt($id);
    }

    /**
     * Get the value of name
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set the value of name
     *
     * @return  self
     */
    public function setName($name)
    {
        $this->name = $this->filterVarString($name);
    }
}
