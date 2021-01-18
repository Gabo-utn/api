<?php
include_once "init.php";

$results = [];
$db = SQL::connect();


//-------------------------------------GET
if (METHOD == "GET" && !defined("ID")){
    $sql = "SELECT id, name FROM Heroes";
    
    $params = null;
    if (isset( $_GET["name"])){
        $params = ["%" . $_GET["name"] . "%"];
        $sql = $sql . " WHERE name LIKE ? ";
    };

    $stmt = SQL::query($db, $sql, $params);

    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $results[] = $row;
    }
}

//-------------------------------------GET/id
if (METHOD == "GET" && defined("ID")){
    $stmt = SQL::query($db,
            "SELECT id, name FROM Heroes
            WHERE id = ?", [ID] );

    $results = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
}


//-------------------------------------DELETE/id
if (METHOD == "DELETE" && defined("ID")){
    $stmt = SQL::query($db,
            "DELETE FROM Heroes
            WHERE id = ?", [ID] );

    sqlsrv_fetch($stmt);
}


//-------------------------------------POST
if (METHOD == "POST"){
    $stmt = SQL::query($db,
        "INSERT INTO Heroes ( name )
        VALUES (?);
        SELECT @@IDENTITY id;",
        [DATA["name"]] );

    sqlsrv_fetch($stmt); // INSERT
    sqlsrv_next_result($stmt);// SELECT @@IDENTITY
    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

    $results = DATA;
    $results["id"] = $row["id"];
}

//-------------------------------------PUT
if (METHOD == "PUT"){
    $stmt = SQL::query($db,
            "UPDATE Heroes SET name = ?
            WHERE id = ?", [DATA["name"], DATA["id"]] );

    sqlsrv_fetch($stmt);
    $results = DATA;
}


if (isset($stmt)){
    sqlsrv_free_stmt($stmt);
    SQL::close($db);
}

echo json_encode($results);

?>