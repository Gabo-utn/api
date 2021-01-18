<?php

class Producto
{
    public $table = 'Producto';
    public $fields = 'prodId
                ,prodDescripcion
                ,prodPrecio
                ,CONVERT(VARCHAR, prodFechaAlta, 126) prodFechaAlta
                ,prodBorrado'; 

    public $join = "";
    
    public function getId ($db) {

        $sql = "SELECT $this->fields FROM $this->table
                $this->join
                WHERE prodId = ?";
        
        $stmt = SQL::query($db, $sql, [ID] );

        return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
    }

    public function get ($db) {
        $sql = "SELECT $this->fields FROM $this->table
                $this->join
                WHERE prodBorrado = 0";

        $params = null;
        if (isset( $_GET["prodDescripcion"])){
            $params = ["%" . $_GET["prodDescripcion"] . "%"];
            $sql = $sql . " AND prodDescripcion LIKE ? ";
        };

        $stmt = SQL::query($db, $sql, $params);
        $results = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $results[] = $row;
        }

        return $results;
    }

    public function delete ($db) {
        $stmt = SQL::query($db,
        "UPDATE $this->table SET prodBorrado = 1
        WHERE prodId = ?", [ID] );

        sqlsrv_fetch($stmt);
        return [];
    }

    public function post ($db) {
        $stmt = SQL::query($db,
        "INSERT INTO $this->table
        (prodDescripcion
        ,prodPrecio
        ,prodFechaAlta
        ,prodBorrado)
        VALUES (?,?,GETDATE(),0);

        SELECT @@IDENTITY prodId, CONVERT(VARCHAR, GETDATE(), 126) prodFechaAlta;",
        [DATA["prodDescripcion"], DATA["prodPrecio"]] );

        sqlsrv_fetch($stmt); // INSERT
        sqlsrv_next_result($stmt);// SELECT @@IDENTITY
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

        $results = DATA;
        $results["prodId"] = $row["prodId"];
        $results["prodFechaAlta"] = $row["prodFechaAlta"];
        $results["prodBorrado"] = 0;
        return $results;
    }

    public function put ($db) {
        $stmt = SQL::query($db,
        "UPDATE Producto
        SET prodDescripcion = ?
            ,prodPrecio = ?
        WHERE prodId = ?",
        [
            DATA["prodDescripcion"],
            DATA["prodPrecio"],
            DATA["prodId"]
        ] );

        sqlsrv_fetch($stmt);
        return DATA;
    }


}

?>