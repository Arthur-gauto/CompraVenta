<?php
    class gasto extends Conectar{

        //TODO LISTAR REGISTROS
        public function get_gasto_x_suc_id($suc_id){
            $conectar=parent::Conexion();
            $sql="";
            $sql="SP_L_GASTO_01 ?";
            $query=$conectar->prepare($sql);
            $query->bindValue(1,$suc_id);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        //TODO LISTAR REGISTROS POR ID
        public function get_gasto_x_gas_id($gas_id){
            $conectar=parent::Conexion();
            $sql="";
            $sql="SP_L_GASTO_02 ?";
            $query=$conectar->prepare($sql);
            $query->bindValue(1,$gas_id);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        //TODO ELIMINAR REGISTRO
        public function delete_gasto($gas_id){
            $conectar=parent::Conexion();
            $sql="";
            $sql="SP_D_GASTO_01 ?";
            $query=$conectar->prepare($sql);
            $query->bindValue(1,$gas_id);
            $query->execute();
            
        }

        //TODO REGISTRAR DATOS
        public function insert_gasto($suc_id,$gas_descrip,$gas_tipo,$gas_mon){
            $conectar=parent::Conexion();
            $sql="";
            $sql="SP_I_GASTO_01 ?,?,?,?";
            $query=$conectar->prepare($sql);
            $query->bindValue(1,$suc_id);
            $query->bindValue(2,$gas_descrip);
            $query->bindValue(3,$gas_tipo);
            $query->bindValue(4,$gas_mon);
            $query->execute();
        }

        //TODO ACTUALIZAR DATOS
        public function update_gasto($gas_id,$suc_id,$gas_descrip,$gas_tipo,$gas_mon){
            $conectar=parent::Conexion();
            $sql="";
            $sql="SP_U_GASTO_01 ?,?,?,?,?";
            $query=$conectar->prepare($sql);
            $query->bindValue(1,$gas_id);
            $query->bindValue(2,$suc_id);
            $query->bindValue(3,$gas_descrip);
            $query->bindValue(4,$gas_tipo);
            $query->bindValue(5,$gas_mon);
            $query->execute();
        }

        public function get_gasto_total_stock($suc_id){
            $conectar=parent::Conexion();
            $sql="";
            $sql="SP_L_GASTO_03 ?";
            $query=$conectar->prepare($sql);
            $query->bindValue(1,$suc_id);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }
    }
?>
