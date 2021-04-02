<?php
class MovilBitacora {
    public $table = 'MovilBitacora';
    public $fields = '  mobiId, 
	                    mobiMoseId,
                        mobiMoviId,
	                    mobiServId, 
	                    CONVERT(VARCHAR,mobiFecha, 126) mobiFecha,
	                    mobiObservaciones, 
	                    mobiOdometro, 
	                    mobiProximoOdometro, 
	                    CONVERT(VARCHAR,mobiProximaFecha, 126) mobiProximaFecha, 
	                    mobiIdAnterior, 
	                    mobiIdSiguiente, 
	                    mobiPendiente, 
	                    CONVERT(VARCHAR,mobiFechaAlta, 126) mobiFechaAlta,
	                    mobiBorrado,
                        
                        servNombre,
                        
                        patente,
                        descripcion';
    public $join = "LEFT OUTER JOIN Servicio ON mobiServId = servId";
    public $joinMovil = "LEFT OUTER JOIN AVL_Estructura.dbo.Movil ON mobiMoviId = MovilId";
    //-----------------------------------GET

    public function get($db) {
        $sql = "SELECT TOP 10 $this->fields
                FROM $this->table
                $this->join
                $this->joinMovil
                WHERE mobiBorrado = 0";
        $params = null;

        if(isset($_GET["mobiMoviId"])){
            $params = [$_GET["mobiMoviId"]];
            $sql = $sql . "AND mobiMoviId = ? ";
        }

        if(isset($_GET["mobiPendiente"])){
            $params = [$_GET["mobiPendiente"]];
            $sql = $sql . "AND mobiPendiente = ? ";
        }

        $sql = $sql . " ORDER BY mobiId desc";

        $stmt = SQL::query($db, $sql, $params);

        $results = [];

        while( $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
            $results[] = $row;
        }

        return $results;
    }

    //-----------------------------------DELETE

    public function delete($db,$id) {
        $sql = "UPDATE $this->table
                SET mobiBorrado = 1
                WHERE mobiId = ?";
        $params = [$id];
        $stmt = SQL::query($db, $sql, $params);

        sqlsrv_fetch($stmt);

        return [];
    }

    //-----------------post

    public function post($db) {
        $sql = "INSERT INTO $this->table
                (mobiMoseId,
                mobiMoviId,
	            mobiServId, 
	            mobiFecha,
	            mobiObservaciones, 
	            mobiOdometro, 
	            mobiProximoOdometro, 
	            mobiProximaFecha, 
	            mobiIdAnterior, 
	            mobiIdSiguiente, 
	            mobiPendiente, 
	            mobiFechaAlta,
	            mobiBorrado)
                VALUES(?,?,?,?,?,?,?,?,?,?,?,CONVERT(VARCHAR,GETDATE(),126),0);
                
                SELECT @@IDENTITY mobiId, CONVERT(VARCHAR, GETDATE(),126) mobiFechaAlta;";
        
        $params = [DATA["mobiMoseId"],
                    DATA["mobiMoviId"],
                    DATA["mobiServId"],
                    DATA["mobiFecha"],
                    DATA["mobiObservaciones"],
                    DATA["mobiOdometro"],
                    DATA["mobiProximoOdometro"],
                    DATA["mobiProximaFecha"],
                    DATA["mobiIdAnterior"],
                    DATA["mobiIdSiguiente"],
                    DATA["mobiPendiente"]];
        
        $stmt = SQL::query($db, $sql, $params);

        sqlsrv_fetch($stmt);
        sqlsrv_next_result($stmt);

        $row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC);

        $results = DATA;
        $results["mobiId"] = $row["mobiId"];
        $results["mobiFechaAlta"] = $row["mobiFechaAlta"];
        $results["mobiBorrado"] = 0;

        return $results;
    }

    //-----------------------------------PUT

    public function put($db) {
        $sql = "UPDATE $this->table
                SET mobiServId = ?,
                    mobiFecha = ?,
                    mobiObservaciones = ?,
                    mobiOdometro = ?,
                    mobiProximoOdometro = ?,
                    mobiProximaFecha = ?,
                    mobiPendiente = ?
                WHERE mobiId = ?";

        $params = [DATA["mobiServId"],
                    DATA["mobiFecha"],
                    DATA["mobiObservaciones"],
                    DATA["mobiOdometro"],
                    DATA["mobiProximoOdometro"],
                    DATA["mobiProximaFecha"],
                    DATA["mobiPendiente"],
                    DATA["mobiId"]]; 

        $stmt = SQL::query($db,$sql,$params);

        sqlsrv_fetch($stmt);

        return DATA;
    }


}
?>