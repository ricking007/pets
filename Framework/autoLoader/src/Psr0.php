<?php
namespace Framework\autoLoader;

class Psr0 implements HowToLoad {

	public function autoLoad($className) {
		// PSR-0 AutoLoader as presented in https://github.com/php-fig/fig-standards/blob/master/accepted/PSR-0.md
		$className = ltrim($className, '\\');
		$fileName  = '';
		$namespace = '';
		if ($lastNsPos = strrpos($className, '\\')) {
			$namespace = substr($className, 0, $lastNsPos);
			$className = substr($className, $lastNsPos + 1);
			$fileName  = str_replace('\\', DIRECTORY_SEPARATOR, $namespace) . DIRECTORY_SEPARATOR;
		}
		$fileName .= str_replace('_', DIRECTORY_SEPARATOR, $className) . '.php';

		require_once $fileName;
	}


}
?>