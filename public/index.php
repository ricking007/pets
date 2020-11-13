<?php
ini_set('display_errors', 1);
ini_set('date.timezone', 'America/Bahia');
date_default_timezone_set('America/Bahia');
define('DS', DIRECTORY_SEPARATOR);
define('ROOT', realpath(dirname(__DIR__)) . DS);
define('APP_PATH', ROOT . 'App' . DS);
try {
    require_once APP_PATH . 'config.php';
    require_once APP_PATH . 'autoload.php';
    Framework\core\Session::init();
    Framework\core\Bootstrap::run($autoLoader->request);
} catch (\Exception $e) {
    echo 'index.php: ' . $e->getMessage();
}
?>
