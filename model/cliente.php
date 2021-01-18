<?php
class Cliente
{
  public $table = 'Cliente';
  public $fields = 'cliId
              ,cliNombre
              ,cliApellido
              ,CONVERT(VARCHAR, prodFechaAlta, 126) prodFechaAlta
              ,prodBorrado'; 

  public $join = "";
  
  public function getId ($db) {

      $sql = "SELECT $this->fields FROM $this->table
              $this->join
              WHERE cliId = ?";
      
      $stmt = SQL::query($db, $sql, [ID] );

      return sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);
  }

  public function get ($db) {
      $sql = "SELECT $this->fields FROM $this->table
              $this->join
              WHERE prodBorrado = 0";

      $params = null;
      if (isset( $_GET["cliNombre"])){
          $params = ["%" . $_GET["cliNombre"] . "%"];
          $sql = $sql . " AND cliNombre LIKE ? ";
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
      WHERE cliId = ?", [ID] );

      sqlsrv_fetch($stmt);
      return [];
  }

  public function post ($db) {
      $stmt = SQL::query($db,
      "INSERT INTO $this->table
      (cliNombre
      ,cliApellido
      ,prodFechaAlta
      ,prodBorrado)
      VALUES (?,?,GETDATE(),0);

      SELECT @@IDENTITY cliId, CONVERT(VARCHAR, GETDATE(), 126) prodFechaAlta;",
      [DATA["cliNombre"], DATA["cliApellido"]] );

      sqlsrv_fetch($stmt); // INSERT
      sqlsrv_next_result($stmt);// SELECT @@IDENTITY
      $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

      $results = DATA;
      $results["cliId"] = $row["cliId"];
      $results["prodFechaAlta"] = $row["prodFechaAlta"];
      $results["prodBorrado"] = 0;
      return $results;
  }

  public function put ($db) {
      $stmt = SQL::query($db,
      "UPDATE Cliente
      SET cliNombre = ?
          ,cliApellido = ?
      WHERE clidId = ?",
      [
          DATA["cliNombre"],
          DATA["cliApellido"],
          DATA["cliId"]
      ] );

      sqlsrv_fetch($stmt);
      return DATA;
  }

}

?>