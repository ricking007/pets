<?php
namespace Framework\core;

class Factory
{  
    public static function controller($tipo)
    {
        $tipo = "App\\controllers\\{$tipo}";
        if (class_exists($tipo)){
            return new $tipo();
        } else {
            throw new Exception("Classe: $tipo not exists");
        }
        
    }
    
    public static function modelo($tipo)
    {
        $tipo = "App\\models\\{$tipo}";     
        if (class_exists($tipo)) {
            return new $tipo();
        } else {
            throw new Exception("Modelo: $tipo not exists");
        }
    }
    
    public static function language($tipo)
    {
        $tipo = "App\\language\\{$tipo}";        
        if (class_exists($tipo)) {
            return new $tipo();
        } else {
            throw new Exception("Language: $tipo not exists");
        }
    }

}
