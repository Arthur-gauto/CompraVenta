<?php
    class Documento extends Conectar{
        //TODO LISTAR REGISTROS
        public function get_documento($doc_tipo){
            $conectar=parent::Conexion();
            $sql="";
            $sql="SP_L_DOCUMENTO_01 ?";
            $query=$conectar->prepare($sql);
            $query->bindValue(1,$doc_tipo);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }
    }
?>
