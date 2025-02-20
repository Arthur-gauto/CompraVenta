<?php
    class Subcategoria extends Conectar{

        //TODO LISTAR REGISTROS
        public function get_subcategoria_x_suc_id($suc_id){
            $conectar=parent::Conexion();
            $sql="";
            $sql="SP_L_SUBCATEGORIA_01 ?";
            $query=$conectar->prepare($sql);
            $query->bindValue(1,$suc_id);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        public function get_subcategoria_x_cat_id($cat_id){
            $conectar=parent::Conexion();
            $sql="";
            $sql="SP_L_SUBCATEGORIA_03 ?";
            $query=$conectar->prepare($sql);
            $query->bindValue(1,$cat_id);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        //TODO LISTAR REGISTROS POR ID
        public function get_subcategoria_x_scat_id($scat_id){
            $conectar=parent::Conexion();
            $sql="";
            $sql="SP_L_SUBCATEGORIA_02 ?";
            $query=$conectar->prepare($sql);
            $query->bindValue(1,$scat_id);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        //TODO ELIMINAR REGISTRO
        public function delete_subcategoria($scat_id){
            $conectar=parent::Conexion();
            $sql="";
            $sql="SP_D_SUBCATEGORIA_01 ?";
            $query=$conectar->prepare($sql);
            $query->bindValue(1,$scat_id);
            $query->execute();
            
        }

        //TODO REGISTRAR DATOS
        public function insert_subcategoria($suc_id,$cat_id,$scat_nom){
            $conectar=parent::Conexion();
            $sql="";
            $sql="SP_I_SUBCATEGORIA_01 ?,?,?";
            $query=$conectar->prepare($sql);
            $query->bindValue(1,$suc_id);
            $query->bindValue(2,$cat_id);
            $query->bindValue(3,$scat_nom);
            $query->execute();
        }

        //TODO ACTUALIZAR DATOS
        public function update_subcategoria($scat_id,$suc_id,$scat_nom){
            $conectar=parent::Conexion();
            $sql="";
            $sql="SP_U_SUBCATEGORIA_01 ?,?,?";
            $query=$conectar->prepare($sql);
            $query->bindValue(1,$scat_id);
            $query->bindValue(2,$suc_id);
            $query->bindValue(3,$scat_nom);
            $query->execute();
        }

        public function get_subcategoria_total_stock($suc_id){
            $conectar=parent::Conexion();
            $sql="";
            $sql="SP_L_SUBCATEGORIA_03 ?";
            $query=$conectar->prepare($sql);
            $query->bindValue(1,$suc_id);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }
    }
?>
