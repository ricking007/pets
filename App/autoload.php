<?php

require_once ROOT . "/vendor/autoload.php";

require_once ROOT . 'Framework/autoLoader/src/AutoLoader.php';

$autoLoader = Framework\autoLoader\AutoLoader::getAutoLoader();
$autoLoader->setHowToLoad(new Framework\autoLoader\Psr4()); // Set the Auto Loader to use PSR-4
$autoLoader->request = new Framework\core\Request();
$autoLoader->db = new Framework\core\Database(DB_TYPE, DB_HOST, DB_PORT, DB_NAME, DB_USER, DB_PASS);

//$autoLoader->_language = new Framework\language\Idioma('es_ES');
