<?php
include_once "init.php";
include_once "./model/pedido.php";


$response = [];
$db = SQL::connect();
$model = new Pedido();

//-------------------------------------GET
if (METHOD == "GET" && !defined("ID")){
    $response = $model->get($db);
}

//-------------------------------------GET/id
if (METHOD == "GET" && defined("ID")){
    $response = $model->getId($db);
}


//-------------------------------------DELETE/id
if (METHOD == "DELETE" && defined("ID")){
    $response = $model->delete($db);
}


//-------------------------------------POST
if (METHOD == "POST"){ 
    $response = $model->post($db);
}

//-------------------------------------PUT
if (METHOD == "PUT"){
    $response = $model->put($db);
}



if (isset($stmt)){
    sqlsrv_free_stmt($stmt);
    SQL::close($db);
}

echo json_encode($response);

?>