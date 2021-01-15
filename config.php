<?php

include_once "cors.php";
include_once "sql_srv.php";

defined ('DB_HOST') ? null : define('DB_HOST','192.168.1.5\sisepdev');
defined ('DB_DATABASE') ? null : define('DB_DATABASE', "Curso");
defined ('DB_USERNAME') ? null : define('DB_USERNAME', "sa");
defined ('DB_PASSWORD') ? null : define('DB_PASSWORD', "Digital23");

?>