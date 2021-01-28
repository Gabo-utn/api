<?php
    $app->post('/login', function ($request, $response, $args) {// que es args?

        $res = [];
    
        // esto deberia irse a buscar a la base de datos, si o si!!!
        if(DATA["usuaLogin"] == "admin" && DATA["usuaPassword"] == "admin"){ //SI el usiario es admin y las pass es admin
                
            $res["usuaId"] = 1;
            $res["usuaLogin"] = DATA["usuaLogin"];
            $res["usuaNombre"] = "Usuario ADMIN";

            $token = $res;
            $token["permisos"] =  ["PRODUCTO_DELETE"
                                    ,"CLIENTE_DELETE"
                                    ,"PEDIDO_POST"
                                    ,"PEDIDO_DELETE"];
    
            $res["usuaToken"] = G::CrearToken($token);
        } elseif (DATA["usuaLogin"] == "Carlos" && DATA["usuaPassword"] == "1234"){//SINO HACE ESTO
            $res["usuaId"] = 2;
            $res["usuaLogin"] = DATA["usuaLogin"];
            $res["usuaNombre"] = "Carlos Bernabeu";

            $token = $res;
            $token["permisos"] = [];
    
            $res["usuaToken"] = G::CrearToken($token);

        }else{
            $res["usuaId"] = -1;
            $res["usuaLogin"] = null;
            $res["usuaNombre"] = null;
            $res["usuaToken"] = null;
        }

        $payload = json_encode($res);
        
        $response->getBody()->write($payload);
        return $response->withHeader('Content-Type', 'application/json');
    });
?>