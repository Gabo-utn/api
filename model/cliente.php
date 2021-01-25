<?php

class Cliente
{

  public $table = 'Cliente';
  public $fields = 'cliId
                  ,cliNombre
                  ,cliDireccion
                  ,CONVERT(VARCHAR,cliFechaAlta, 126) cliFechaAlta
                  ,cliBorrado'; 

  public $join = "";
    
  public function get ($db) {
      $sql = "SELECT $this->fields FROM $this->table
              $this->join
              WHERE cliBorrado = 0";

      $stmt = SQL::query($db, $sql, null);
      $results = [];
      while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
          $results[] = $row;
      }

      return $results;
  }
}

?>

?>