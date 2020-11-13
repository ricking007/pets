<?php
namespace Framework\autoLoader;

use Exception;

class Psr4 implements HowToLoad 
{
    public function autoLoad($className)
    {
        // PSR-4 AutoLoader as presented in https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-4-autoloader.md
        $className = ltrim($className, '\\');		
        $className = explode('\\', $className);

        $fileName = $className[0];
        for ($i = 1; $i < sizeof($className); $i++) {
                if ($i == count($className)-1) {
                        $fileName .= DIRECTORY_SEPARATOR . 'src';
                }
                $fileName .= DIRECTORY_SEPARATOR . $className[$i]; 
        }
        $fileName .= '.php';

        //echo $fileName.'<br>';

        if (is_readable(ROOT . $fileName)) {
            require_once ROOT . $fileName;
        } else {
            throw new Exception('<br>Class not found: '.$fileName);
        }

    }

}