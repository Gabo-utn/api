<?php

class Pedido
{
    public $table = 'Pedido';
    public $fields = 'pediId
        ,CONVERT(VARCHAR, pediFecha, 126) pediFecha
        ,pediClienId
        ,CONVERT(VARCHAR, pediFechaAlta, 126) pediFechaAlta
        ,pediBorrado
        ,cliNombre'; 

    public $join = " LEFT OUTER JOIN Cliente on pediCliId = cliId";
    
    public function get ($db) {
        $sql = "SELECT $this->fields FROM $this->table
                $this->join
                WHERE pediBorrado = 0";

        $stmt = SQL::query($db, $sql, null);
        $results = [];
        while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $results[] = $row;
        }

        return $results;
    }

    public function delete ($db) {
        $stmt = SQL::query($db,
        "UPDATE $this->table SET pediBorrado = 1
        WHERE pediId = ?", [ID] );

        sqlsrv_fetch($stmt);
        return [];
    }

    public function post ($db) {
        $stmt = SQL::query($db,
        "INSERT INTO $this->table
            (pediFecha
            ,pediCliId
            ,pediFechaAlta
            ,pediBorrado)
        VALUES (?,?,GETDATE(),0);
        SELECT @@IDENTITY pediId, CONVERT(VARCHAR, GETDATE(), 126) pediFechaAlta;",
        [DATA["pediFecha"], DATA["pediCliId"]] );

        sqlsrv_fetch($stmt); // INSERT
        sqlsrv_next_result($stmt);// SELECT @@IDENTITY
        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

        $results = DATA;
        $results["pediId"] = $row["pediId"];
        $results["pediFechaAlta"] = $row["pediFechaAlta"];
        $results["pediBorrado"] = 0;
        return $results;
    }

    public function put ($db) {
        $stmt = SQL::query($db,
        "UPDATE Pedido
        SET pediFecha = ?
            ,pediCliId = ?
        WHERE pediId = ?",
        [
            DATA["pediFecha"],
            DATA["pediCliId"],
            DATA["pediId"]
        ] );

        sqlsrv_fetch($stmt);
        return DATA;
    }


}

?>