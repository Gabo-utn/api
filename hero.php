<?php

include_once "config.php";

$id = 0;
if (isset($_SERVER['PATH_INFO'])) {
    $id = basename($_SERVER['PATH_INFO']);
}

$input = file_get_contents("php://input");
$data = json_decode($input, true);

$method = $_SERVER['REQUEST_METHOD'];
$results = [];

$db = SQL::connect();

//-----------------------------------GET
//----------------------------------- busqueda / lista completa de heroes 

if ($method == 'GET' && $id == 0) {
    $sql = "SELECT id, name FROM Heroes";

    if(isset($_GET["name"])) {
        $sql = $sql . " WHERE name LIKE '%" . $_GET["name"] . "%'";
    };

    $stmt = SQL::query($db, $sql, null);

    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)){
        $results[] = $row;
    }
}

//----------------------------------GET/id
//---------------------------------- un solo heroe

if ($method == 'GET' && $id > 0) {
    $stmt = SQL::query($db, 
    "SELECT id, name FROM Heroes WHERE id = ?",
    [$id]);

    $results = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
}

//---------------------------------DELETE
//--------------------------------- borrar heroe

if ($method == "DELETE" && $id > 0) {
    $stmt = SQL::query($db, 
    "DELETE FROM Heroes WHERE id = ?",
    [$id]);

    sqlsrv_fetch($stmt);
}

//--------------------------------POST
//-------------------------------- agregar heroe

if($method == "POST" && $id > 0) {
    
    $stmt = SQL::query($db, 
    "INSERT INTO Heroes ( name ) VALUES (?);
     SELECT @@IDENTITY id;",
    [$data["name"]] );

    sqlsrv_fetch($stmt);
    sqlsrv_next_result($stmt);

    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

    $results[] = $data;

    $results["id"] = $row["id"];
};

//-------------------------------PUT
//------------------------------- actualizar heroes
if($method == 'PUT' && $id >0) {
    $stmt = SQL::query($db,
    "UPDATE Heroes SET name = ? WHERE id = ?",
    [$data["name"], $data["id"]]);

    sqlsrv_fetch($stmt);

    $results = $data;
}

if(isset($stmt)) {
    sqlsrv_free_stmt($stmt);
}

echo json_encode($results);

?>