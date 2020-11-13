<?php
namespace Framework\autoLoader;

require_once 'HowToLoad.php';
require_once 'Psr4.php';
require_once 'Psr0.php';

class AutoLoader 
{
    // singleton
    private static $autoLoader;

    // strategy
    private $howToLoad;

    private function __construct() {}

    public static function getAutoLoader() 
    {
        // return the only autoLoader instance
        if (self::$autoLoader == null) {
            self::$autoLoader = new AutoLoader();
        }
        return self::$autoLoader;
    }

    public function setHowToLoad($psr)
    {
        $this->howToLoad = $psr;
        spl_autoload_register(array($this->howToLoad,'autoLoad'));
    }

}