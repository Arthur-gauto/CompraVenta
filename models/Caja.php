<?php
    class Caja extends Conectar{

        //TODO LISTAR REGISTROS
        public function get_caja_x_suc_id($suc_id){
            $conectar=parent::Conexion();
            $sql="";
            $sql="SP_L_CAJA_01 ?";
            $query=$conectar->prepare($sql);
            $query->bindValue(1,$suc_id);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        public function get_caja_x_caj_id($caj_id){
            $conectar=parent::Conexion();
            $sql="";
            $sql="SP_L_CAJA_02 ?";
            $query=$conectar->prepare($sql);
            $query->bindValue(1,$caj_id);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        

        //TODO ELIMINAR REGISTRO
        public function delete_caja($caJ_id){
            $conectar=parent::Conexion();
            $sql="";
            $sql="SP_D_CAJA_01 ?";
            $query=$conectar->prepare($sql);
            $query->bindValue(1,$caJ_id);
            $query->execute();
            
        }

        //TODO REGISTRAR DATOS
        public function insert_caja($suc_id,$usu_id,$caj_ini){
            $conectar=parent::Conexion();
            $sql="";
            $sql="SP_I_CAJA_01 ?,?,?";
            $query=$conectar->prepare($sql);
            $query->bindValue(1,$suc_id);
            $query->bindValue(2,$usu_id);
            $query->bindValue(3,$caj_ini);
            $query->execute();
        }

        //TODO ACTUALIZAR DATOS
        public function update_caja($caj_id,$caj_ing,$caj_egr,$caj_fin){
            $conectar=parent::Conexion();
            $sql="";
            $sql="SP_U_CAJA_01 ?,?,?,?";
            $query=$conectar->prepare($sql);
            $query->bindValue(1,$caj_id);
            $query->bindValue(2,$caj_ing);
            $query->bindValue(3,$caj_egr);
            $query->bindValue(4,$caj_fin);
            $query->execute();
        }

        public function verificar_caja_abierta($suc_id) {
            $conectar = parent::conexion();
            $sql = "SELECT * FROM TM_CAJA WHERE SUC_ID = ? AND EST = 1";
            $stmt = $conectar->prepare($sql);
            $stmt->bindValue(1, $suc_id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC); // Devuelve la caja si existe, o false si no
        }

        public function datos_caja($suc_id) {
            $conectar = parent::conexion();
            $sql = "SP_L_CAJA_03 ?";
            $stmt = $conectar->prepare($sql);
            $stmt->bindValue(1, $suc_id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC); 
        }

        public function get_caja_abierta(){
            $conectar=parent::Conexion();
            $sql="";
            $sql="SP_L_CAJA_03";
            $query=$conectar->prepare($sql);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
            
        }

        public function verificar_caja($suc_id) {
            $conectar = parent::Conexion();  
            $sql = "SP_V_VERIFICAR";  
            $query = $conectar->prepare($sql);
            $query->execute();
            $row = $query->fetch(PDO::FETCH_ASSOC);
            return $row['CajaAbierta'];
        }

        public function calculo_egr($caj_id){
            $conectar=parent::Conexion();
            $sql="";
            $sql="SP_L_CAJA_04 ?";
            $query=$conectar->prepare($sql);
            $query->bindValue(1,$caj_id);
            $query->execute();
            return $query->fetch(PDO::FETCH_ASSOC); 


        }
        public function update_caja_egr($caj_id,$caj_egr){
            $conectar=parent::Conexion();
            $sql="";
            $sql="SP_U_CAJA_02 ?,?";
            $query=$conectar->prepare($sql);
            $query->bindValue(1,$caj_id);
            $query->bindValue(2,$caj_egr);
            $query->execute();
        }

        public function update_caja_ing($caj_id,$caj_ing){
            $conectar=parent::Conexion();
            $sql="";
            $sql="SP_U_CAJA_03 ?,?";
            $query=$conectar->prepare($sql);
            $query->bindValue(1,$caj_id);
            $query->bindValue(2,$caj_ing);
            $query->execute();
        }

        public function calculo_ing($caj_id){
            $conectar=parent::Conexion();
            $sql="";
            $sql="SP_L_CAJA_05 ?";
            $query=$conectar->prepare($sql);
            $query->bindValue(1,$caj_id);
            $query->execute();
            return $query->fetch(PDO::FETCH_ASSOC); 


        }

        public function get_caja_detalle($caj_id){
            $conectar=parent::Conexion();
            $sql="";
            $sql="SP_L_CAJA_06 ?";
            $query=$conectar->prepare($sql);
            $query->bindValue(1,$caj_id);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }
        
    }
?>