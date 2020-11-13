<?php
namespace Framework\core;

use PDO;

class Database extends PDO
{
    public function __construct($type,$host, $port, $dbname, $user, $pass) 
    {
        parent::__construct(
                "$type:host=$host;port=$port;dbname=$dbname",
                "$user",
                "$pass"
        );
    }
}