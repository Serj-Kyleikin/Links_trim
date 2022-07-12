<?php

if(isset($_POST['url'])) {

    require_once $_SERVER['DOCUMENT_ROOT'] . '/application/core/Model.php';
    $path = '\application\core\Model';
    new $path;

} else {
    header('location: /');
}