<?php
    class Pago extends Conectar{
        //TODO LISTAR REGISTROS
        public function get_pago(){
            $conectar=parent::Conexion();
            $sql="";
            $sql="SP_L_PAGO_01";
            $query=$conectar->prepare($sql);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }
    }
?>
