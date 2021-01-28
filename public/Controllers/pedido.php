<?php
include_once "model/pedido.php";


$app->get('/pedido', function ($request, $response, $args) {
    //$token = G::Autenticar($request, "ADMIN_VER");

    $db = SQL::connect();
    $model = new Pedido();

    $results = $model->get($db);//modelo .get
    SQL::close($db);

    $payload = json_encode($results);

    $response->getBody()->write($payload);
    return $response // devuelve un objeto
              ->withHeader('Content-Type', 'application/json');
    });


$app->delete('/pedido/{id}', function ($request, $response, $args) {
    $token = G::Autenticar($request, "PEDIDO_DELETE");

    $id = $args['id'];

    $db = SQL::connect();// conectamos a la base
    $model = new Pedido();// al modelo

    $results = $model->delete($db, $id);
    SQL::close($db);

    $payload = json_encode($results);//string con un json_encode

    $response->getBody()->write($payload);
    return $response
              ->withHeader('Content-Type', 'application/json');
    });

$app->put('/pedido', function ($request, $response, $args) {// 
        //$token = G::Autenticar($request, "ADMIN_MODIFICAR");
    
        $db = SQL::connect();
        $model = new Pedido();
    
        $results = $model->put($db);

        SQL::close($db);

        $payload = json_encode($results);
    
        $response->getBody()->write($payload);
        return $response
                  ->withHeader('Content-Type', 'application/json');
});

$app->post('/pedido', function ($request, $response, $args) {
    $token = G::Autenticar($request, "PEDIDO_POST");

    $db = SQL::connect();
    $model = new Pedido();

    $results = $model->post($db);

    SQL::close($db);

    $payload = json_encode($results);

    $response->getBody()->write($payload);
    return $response
              ->withHeader('Content-Type', 'application/json');
});

?>