<?php
include_once "config.php";
include_once "sql_srv.php";
$id=0;
if (isset($_SERVER['PATH_INFO'])){$id = basename($_SERVER['PATH_INFO']);
}
//lo que me devuelve es la ultima parte de la ruta
$input = file_get_contents("php://input");
$data = json_decode($input, true);
$method = $_SERVER['REQUEST_METHOD'];
$results=[];
$db = SQL::connect();



//--GET
if ($method == "GET" && $id == 0){
    $sql = "SELECT prodId
            ,prodDescripcion
            ,prodPrecio
            ,CONVERT(VARCHAR, prodFechaAlta, 126) prodFechaAlta
            ,prodBorrado
            FROM Producto
            WHERE prodBorrado = 0";

    $params =null;
    if (isset( $_GET["prodDescripcion"])){// isset verifica si la variable <> null
        
        $params = ["%" . $_GET["prodDescripcion"] . "%"];
        $sql = $sql . " WHERE prodDescripcion LIKE ?" ;// cualquer palabra que tenga  getname en el medio lo trae
    };

    $stmt = SQL::query($db, $sql, $params);

    while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
        $results[] = $row;
    }
}
//---GET/id
if ($method == "GET" && $id > 0 ){
    $stmt =  SQL::query($db,
    "SELECT id,name FROM Heroes
    WHERE id = ?", [$id])  ;
    $results = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

}
if ($method == "DELETE"&& $id>0){
    $stmt = SQL::query($db,
            "DELETE FROM Heroes
            where id= ? ",[id]);
    sqlsrv_fetch($stmt);
}
//sqlsrv_fetch devuelve un conjunto de datos en forma de una instancia,
//donde estos valores, se corresponde con los de la fila recuperada
//--POST es un insert

if($method =="POST"){
    $stmt == SQL::query($db,
        "INSERT INTO HEROES(name)
        values (?);
        SELECT @@IDENTITY id;",
        [$data["name"]]);

//identity" permite indicar el valor de inicio de la secuencia y el incremento
 sqlsrv_fetch($stmt); //insert
 sqlsrv_next_result($stmt);//select identity
 //Activa el siguiente resultado de la instrucción especificada. Los resultados incluyen conjuntos de resultados, recuentos de filas y parámetros de salida.
 $row = sqlsrv_fetch_array($stmt,SQLSRV_FETCH_ASSOC);//convierte el resgistro del fecht en un array
 $results = $data;
 $result[$id] = $row["id"]; // lo que tengo en mi variable result le asigno lo que traje del row
}
//PUT
if($method =="PUT"){
    $stmt == SQL ::query($db,
    "UPDATE Heroes SET name=?
    WHERE id= ?", [$data["name"],$data["id"]]);
    sqlsrv_fetch($stmt);
    $result = $data;


}
if (isset ($stmt)){
    sqlsrv_free_stmt($stmt);
    /*Libera todos los recursos para la declaración especificada.
     La declaración no se puede usar después de que se haya llamado
      a sqlsrv_free_stmt () . Si se llama a sqlsrv_free_stmt () 
      en una declaración en curso que altera el estado del servidor,
     la ejecución de la declaración finaliza y la declaración se revierte.
     */
    SQL::close($db);


}
echo json_encode($results)

?>
