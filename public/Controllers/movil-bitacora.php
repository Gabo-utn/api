<?php
include_once 'modules/movil-bitacora.php';

//----------------------------------GET

$app->get("/movil-bitacora",function ($request, $response, $args){
    $db = SQL::connect();
    $model = new MovilBitacora();
    $results = $model->get($db);
    SQL::close($db);

    $payload = json_encode($results);
    $response->getBody()->write($payload);

    return $response
                    ->withHeader('Content-type','aplication/json');
});

//----------------------------------DELETE

$app->delete("/movil-bitacora/{id}",function ($request, $response, $args){
    $id = $args['id'];
    $db = SQL::connect();
    $model = new MovilBitacora();
    $results = $model->delete($db,$id);
    SQL::close($db);

    $payload = json_encode($results);
    $response->getBody()->write($payload);

    return $response
                    ->withHeader('Content-type','aplication/json');
});


//----------------------------------POST

$app->post("/movil-bitacora",function ($request, $response, $args){
    $db = SQL::connect();
    $model = new MovilBitacora();
    $results = $model->post($db);
    SQL::close($db);

    $payload = json_encode($results);
    $response->getBody()->write($payload);

    return $response
                    ->withHeader('Content-type','aplication/json');
});

//----------------------------------PUT

$app->put("/movil-bitacora",function ($request, $response, $args){
    $db = SQL::connect();
    $model = new MovilBitacora();
    $results = $model->put($db);
    SQL::close($db);

    $payload = json_encode($results);
    $response->getBody()->write($payload);

    return $response
                    ->withHeader('Content-type','aplication/json');
});

?>