<?php
namespace Framework\language;

class Idioma 
{
    private $languageArray;

    public function __construct($idioma)
    {
        $this->languageArray = self::setLanguage($idioma);
    }
    
    private static function setLanguage($idioma)
    {
        $file = dirname(__DIR__) . DIRECTORY_SEPARATOR . $idioma . '.ini';
        //echo $file; exit();
        
        if (!file_exists($file)) {
            echo "error carregando idioma.";
            exit();
        }
        
        return parse_ini_file($file);
    }
    
    public function _($str) { 
        if (array_key_exists($str, $this->languageArray)) {
            return $this->languageArray[$str];
        }
        return "-.-";
    }
    
    public function getSave()
    {
        return $this->languageArray['SAVE'];
    }

    public function getCancel()
    {
        return $this->languageArray['CANCEL'];
    }

}