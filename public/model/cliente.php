<?php


class Cliente
{

  public $table = 'Cliente';
  public $fields = 'clienId
                  ,clienNombre
                  ,clienDireccion
                  ,CONVERT(VARCHAR,clienFechaAlta, 126) clienFechaAlta
                  ,clienBorrado'; 

  public $join = "";
    
  public function get ($db) {
      $sql = "SELECT $this->fields FROM $this->table
              $this->join
              WHERE clienBorrado = 0";

      $stmt = SQL::query($db, $sql, null);
      $results = [];
      while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
          $results[] = $row;
      }

      return $results;
  }
}

?>