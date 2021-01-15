<?php
include_once 'init.php';

$results = [];
$db = SQL::connect();

//-------------------------------------GET
//-------------------------------------buscar / lista productos

if (METHOD == 'GET' && !defined("ID")) {
    $sql = "SELECT prodId,
            prodDescripcion, 
            prodPrecio,
            CONVERT(VARCHAR, prodFechaAlta, 126) prodFechaAlta,
            prodBorrado
            FROM Producto
            WHERE prodBorrado = 0";

    $params=null;
    
    if (isset( $_GET["prodDescripcion"])){
        $params = ["%" . $_GET["prodDescripcion"] . "%"];
        $sql = $sql . " AND prodDescripcion LIKE ? ";
    };

    $stmt = SQL::query($db, $sql, $params);

    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $results[] = $row;
    }
}

//------------------------------------GET/ID
//------------------------------------un solo producto

if (METHOD == 'GET' && defined("ID")) {
    $stmt = SQL::query ($db,
            "SELECT prodId,
            prodDescripcion, 
            prodPrecio,
            CONVERT(VARCHAR, prodFechaAlta, 126) prodFechaAlta,
            prodBorrado
            FROM Producto
            WHERE prodId = ? ",
            [ID]);

    $results = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
}

//------------------------------------DELETE
//------------------------------------borrar producto

if (METHOD == 'DELETE' && defined("ID")) {
    $stmt = SQL::query ($db,
            "UPDATE Producto
            SET prodBorrado = 1
            WHERE prodId = ?",
            [ID]);
    
    $results = [];
}


//-------------------------------------POST
//-------------------------------------agregar producto

if (METHOD == 'POST') {
    $stmt = SQL::query($db,

    "INSERT INTO Producto
    ( prodDescripcion,
    prodPrecio,
    prodFechaAlta,
    prodBorrado)
    VALUES (?,?,GETDATE(),0);

    SELECT @@IDENTITY prodId, CONVERT(VARCHAR, GETDATE(), 126) prodFechaAlta;",

    [DATA["prodDescripcion"],DATA["prodPrecio"]]
    );

    sqlsrv_fetch($stmt);
    sqlsrv_next_result($stmt);

    $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

    $results[] = DATA;
    $results['prodId'] = $row['prodId'];
    $results['prodFechaAlta'] = $row['prodFechaAlta'];
    $results['prodBorrado'] = 0;
}

//----------------------------------PUT
//----------------------------------actualizar producto

if (METHOD == 'PUT') {
    $stmt = SQL::query($db,
    "UPDATE Producto
    SET prodDescripcion = ?,
        prodPrecio = ?
    WHERE prodId = ?",
    [DATA["prodDescripcion"], DATA["prodPrecio"], DATA["prodId"]]);

    $results = [];
}

if (isset($stmt)) {
    sqlsrv_free_stmt($stmt);
    SQL::close($db);
}


echo json_encode($results)


?>