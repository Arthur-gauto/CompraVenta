<?php
    class Compra extends Conectar{

        //TODO LISTAR REGISTROS POR ID
        public function insert_compra_x_suc_id($suc_id, $usu_id, $nro_fact, $fech_fact){
            $conectar = parent::Conexion();
            $sql = "SP_I_COMPRA_01 ?,?,?,?";
            $query = $conectar->prepare($sql);
            $query->bindValue(1, $suc_id);
            $query->bindValue(2, $usu_id);
            $query->bindValue(3, $nro_fact);
            $query->bindValue(4, $fech_fact);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }
        

        public function insert_compra_detalle($compr_id, $prod_id, $prod_pcompra, $detc_cant){
            $conectar=parent::Conexion();
            $sql="";
            $sql="SP_I_COMPRA_02 ?,?,?,?";
            $query=$conectar->prepare($sql);
            $query->bindValue(1,$compr_id);
            $query->bindValue(2,$prod_id);
            $query->bindValue(3,$prod_pcompra);
            $query->bindValue(4,$detc_cant);
            $query->execute();
            //return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        public function get_compra_detalle($compr_id){
            $conectar=parent::Conexion();
            $sql="";
            $sql="SP_L_COMPRA_01 ?";
            $query=$conectar->prepare($sql);
            $query->bindValue(1,$compr_id);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        public function delete_compra_detalle($detc_id){
            $conectar=parent::Conexion();
            $sql="";
            $sql="SP_D_COMPRA_01 ?";
            $query=$conectar->prepare($sql);
            $query->bindValue(1,$detc_id);
            $query->execute();
            
        }
        
        public function get_compra_calculo($compr_id){
            $conectar=parent::Conexion();
            $sql="";
            $sql="SP_U_COMPRA_01 ?";
            $query=$conectar->prepare($sql);
            $query->bindValue(1,$compr_id);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        public function update_compra($compr_id, $pag_id, $prov_id, $prov_ruc, $prov_direcc, $prov_correo, $compr_coment, $mon_id, $doc_id, $nro_fact, $fech_fact){
            $conectar = parent::Conexion();
            $sql = "SP_U_COMPRA_03 ?,?,?,?,?,?,?,?,?,?,?";
            $query = $conectar->prepare($sql);
            $query->bindValue(1, $compr_id);
            $query->bindValue(2, $pag_id);
            $query->bindValue(3, $prov_id);
            $query->bindValue(4, $prov_ruc);
            $query->bindValue(5, $prov_direcc);
            $query->bindValue(6, $prov_correo);
            $query->bindValue(7, $compr_coment);
            $query->bindValue(8, $mon_id);
            $query->bindValue(9, $doc_id);
            $query->bindValue(10, $nro_fact);
            $query->bindValue(11, $fech_fact);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }
        
        

        public function get_compra($compr_id){
            $conectar=parent::Conexion();
            $sql="";
            $sql="SP_L_COMPRA_02 ?";
            $query=$conectar->prepare($sql);
            $query->bindValue(1,$compr_id);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        public function get_compra_listado($suc_id){
            $conectar=parent::Conexion();
            $sql="";
            $sql="SP_L_COMPRA_03 ?";
            $query=$conectar->prepare($sql);
            $query->bindValue(1,$suc_id);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        public function get_compra_top_productos($suc_id){
            $conectar=parent::Conexion();
            $sql="";
            $sql="SP_L_COMPRAS_04 ?";
            $query=$conectar->prepare($sql);
            $query->bindValue(1,$suc_id);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        public function get_compra_top_5($suc_id){
            $conectar=parent::Conexion();
            $sql="";
            $sql="SP_L_COMPRA_05 ?";
            $query=$conectar->prepare($sql);
            $query->bindValue(1,$suc_id);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        public function get_compraventa($suc_id){
            $conectar=parent::Conexion();
            $sql="";
            $sql="SP_L_COMPRA_VENTA_01 ?";
            $query=$conectar->prepare($sql);
            $query->bindValue(1,$suc_id);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        public function get_consumocompra_categoria($suc_id){
            $conectar=parent::Conexion();
            $sql="";
            $sql="SP_L_COMPRA_04 ?";
            $query=$conectar->prepare($sql);
            $query->bindValue(1,$suc_id);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        public function get_compra_barras($suc_id){
            $conectar=parent::Conexion();
            $sql="";
            $sql="SP_L_COMPRA_05 ?";
            $query=$conectar->prepare($sql);
            $query->bindValue(1,$suc_id);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        public function insert_compra_con_detalle($data) {
            $conectar = parent::Conexion();
            $sql = "EXEC SP_I_COMPRA_03 ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?";
            $query = $conectar->prepare($sql);
            $query->bindValue(1, $data['SUC_ID']);
            $query->bindValue(2, $data['PAG_ID']);
            $query->bindValue(3, $data['PROV_ID']);
            $query->bindValue(4, $data['PROV_RUC']);
            $query->bindValue(5, $data['PROV_DIRECC']);
            $query->bindValue(6, $data['PROV_CORREO']);
            $query->bindValue(7, $data['NRO_FACT']);
            $query->bindValue(8, $data['FECH_FACT']);
            $query->bindValue(9, $data['COMPR_SUBTOTAL']);
            $query->bindValue(10, $data['COMPR_IGV']);
            $query->bindValue(11, $data['COMPR_TOTAL']);
            $query->bindValue(12, $data['COMPR_COMENT']);
            $query->bindValue(13, $data['USU_ID']);
            $query->bindValue(14, $data['MON_ID']);
            $query->bindValue(15, $data['DOC_ID']);
            $query->bindValue(16, $data['FECH_CREA']);
            $query->bindValue(17, $data['EST']);
            $query->bindValue(18, $data['CAJA_ID']);
            $query->bindValue(19, json_encode($data['detalle']));
    
            $query->execute();
    
            return $conectar->lastInsertId();
        }
    }
?>