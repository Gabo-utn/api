<?php
include_once "cors.php";
include_once "sql_srv.php";
include_once "config.php";


if (isset($_SERVER['PATH_INFO'])){
    define('ID', basename($_SERVER['PATH_INFO']));
}

$input = file_get_contents("php://input");
define('DATA', json_decode($input, true));

define('METHOD', $_SERVER['REQUEST_METHOD']);


?>