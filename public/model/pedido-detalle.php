<?php

class PedidoDetalle
{
    public $table = 'PedidoDetalle';
    public $fields = 'detaId
            ,detaPediId
            ,detaProdId
            ,detaCantidad
            ,detaPrecio
            ,CONVERT(VARCHAR, detaFechaAlta, 126) detaFechaAlta
            ,detaBorrado
            ,prodDescripcion'; 

    public $join = " LEFT OUTER JOIN Producto ON detaProdId = prodId";
    
    public function get ($db) {
        $sql = "SELECT TOP (1000) $this->fields FROM $this->table
                $this->join
                WHERE detaBorrado = 0";

        $params = null;
        if (isset( $_GET["detaPediId"])){
            $params = [$_GET["detaPediId"]];
            $sql = $sql . " AND detaPediId = ? ";
        };
        

        $stmt = SQL::query($db, $sql, $params);
        $results = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $results[] = $row;
        }

        return $results;
    }

    public function delete ($db, $id) {
        //var_dump(ID); die;
        $stmt = SQL::query($db,
        "UPDATE $this->table SET detaBorrado = 1
        WHERE detaId = ?", [$id] );

        sqlsrv_fetch($stmt);
        return [];
    }

    public function post ($db) {
        $stmt = SQL::query($db,
        "INSERT INTO $this->table
            (detaPediId
            ,detaProdId
            ,detaCantidad
            ,detaPrecio
            ,detaFechaAlta
            ,detaBorrado)
        VALUES (?,?,?,?,GETDATE(),0);
        SELECT @@IDENTITY detaId, CONVERT(VARCHAR, GETDATE(), 126) detaFechaAlta;",
        [ DATA["detaPediId"]
        ,DATA["detaProdId"]
        ,DATA["detaCantidad"]
        ,DATA["detaPrecio"]] );

        sqlsrv_fetch($stmt); // INSERT
        sqlsrv_next_result($stmt);// SELECT @@IDENTITY
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

        $results = DATA;
        $results["detaId"] = $row["detaId"];
        $results["detaFechaAlta"] = $row["detaFechaAlta"];
        $results["detaBorrado"] = 0;
        return $results;
    }

    public function put ($db) {
        $stmt = SQL::query($db,
        "UPDATE PedidoDetalle
        SET detaProdId = ?
            ,detaCantidad = ?
            ,detaPrecio = ?
        WHERE detaId = ?",
        [
            DATA["detaProdId"],
            DATA["detaCantidad"],
            DATA["detaPrecio"],
            DATA["detaId"]
        ] );

        sqlsrv_fetch($stmt);
        return DATA;
    }


}

?>