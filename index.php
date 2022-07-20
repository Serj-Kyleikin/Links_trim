<?php

session_start();

// Настройки администратора

ini_set('display_errors', 1);       // 0 - публичное размещение. 1 - отладка.
error_reporting(E_ALL);

define('A_MODE', 0);                // Режим администратора

if(A_MODE) require_once $_SERVER['DOCUMENT_ROOT'] . '/libraries/Admin.php';

// Автозагрузка классов

spl_autoload_register(function($class) {

	$ds = DIRECTORY_SEPARATOR;
    $path = $_SERVER['DOCUMENT_ROOT'] . $ds . str_replace('\\', $ds, $class) . '.php';

    if(file_exists($path)) require $path;
});

$path = '\application\core\Controller';
new $path;