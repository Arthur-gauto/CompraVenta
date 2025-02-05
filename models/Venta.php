<?php
    class Venta extends Conectar{

        //TODO LISTAR REGISTROS POR ID
        public function insert_venta_x_suc_id($suc_id, $usu_id, $nro_factv, $fech_factv){
            $conectar=parent::Conexion();
            $sql="";
            $sql="SP_I_VENTA_01 ?,?,?,?";
            $query=$conectar->prepare($sql);
            $query->bindValue(1,$suc_id);
            $query->bindValue(2,$usu_id);
            $query->bindValue(3,$nro_factv);
            $query->bindValue(4,$fech_factv);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        public function insert_venta_detalle($vent_id, $prod_id, $prod_pventa, $detv_cant){
            $conectar=parent::Conexion();
            $sql="";
            $sql="SP_I_VENTA_02 ?,?,?,?";
            $query=$conectar->prepare($sql);
            $query->bindValue(1,$vent_id);
            $query->bindValue(2,$prod_id);
            $query->bindValue(3,$prod_pventa);
            $query->bindValue(4,$detv_cant);
            $query->execute();
            //return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        public function get_venta_detalle($vent_id){
            $conectar=parent::Conexion();
            $sql="";
            $sql="SP_L_VENTA_01 ?";
            $query=$conectar->prepare($sql);
            $query->bindValue(1,$vent_id);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        public function delete_venta_detalle($detv_id){
            $conectar=parent::Conexion();
            $sql="";
            $sql="SP_D_VENTA_01 ?";
            $query=$conectar->prepare($sql);
            $query->bindValue(1,$detv_id);
            $query->execute();
            
        }
        
        public function get_venta_calculo($vent_id){
            $conectar=parent::Conexion();
            $sql="";
            $sql="SP_U_VENTA_01 ?";
            $query=$conectar->prepare($sql);
            $query->bindValue(1,$vent_id);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        public function update_venta($vent_id, $pag_id, $cli_id, $cli_ruc, $cli_direcc, $cli_correo, $vent_coment, $mon_id,$doc_id, $nro_factv, $fech_factv){
            $conectar=parent::Conexion();
            $sql="";
            $sql="SP_U_VENTA_03 ?,?,?,?,?,?,?,?,?,?,?";
            $query=$conectar->prepare($sql);
            $query->bindValue(1,$vent_id);
            $query->bindValue(2,$pag_id);
            $query->bindValue(3,$cli_id);
            $query->bindValue(4,$cli_ruc);
            $query->bindValue(5,$cli_direcc);
            $query->bindValue(6,$cli_correo);
            $query->bindValue(7,$vent_coment);
            $query->bindValue(8,$mon_id);
            $query->bindValue(9,$doc_id);
            $query->bindValue(10,$nro_factv);
            $query->bindValue(11,$fech_factv);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        public function get_venta($vent_id){
            $conectar=parent::Conexion();
            $sql="";
            $sql="SP_L_VENTA_02 ?";
            $query=$conectar->prepare($sql);
            $query->bindValue(1,$vent_id);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        public function get_venta_listado($suc_id){
            $conectar=parent::Conexion();
            $sql="";
            $sql="SP_L_VENTA_03 ?";
            $query=$conectar->prepare($sql);
            $query->bindValue(1,$suc_id);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        public function get_venta_top_productos($suc_id){
            $conectar=parent::Conexion();
            $sql="";
            $sql="SP_L_VENTA_04 ?";
            $query=$conectar->prepare($sql);
            $query->bindValue(1,$suc_id);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        public function get_venta_barras($suc_id){
            $conectar=parent::Conexion();
            $sql="";
            $sql="SP_L_VENTA_05 ?";
            $query=$conectar->prepare($sql);
            $query->bindValue(1,$suc_id);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        public function insertar_venta_rapida($data) {
            try {
                $conectar = parent::Conexion();
                
                // Insertar cabecera de venta
                $sql = "INSERT INTO TM_VENTA (
                            SUC_ID,
                            USU_ID,
                            PAG_ID,
                            CLI_ID,
                            CLI_RUC,
                            CLI_DIRECC,
                            CLI_CORREO,
                            NRO_FACTV,
                            FECH_FACTV,
                            VENT_SUBTOTAL,
                            VENT_IGV,
                            VENT_TOTAL,
                            VENT_COMENT,
                            MON_ID,
                            DOC_ID,
                            FECH_CREA,
                            EST
                        ) VALUES (
                            ?, -- SUC_ID
                            ?, -- USU_ID
                            1, -- PAG_ID (efectivo por defecto)
                            1, -- CLI_ID (cliente general)
                            0, -- CLI_RUC
                            '', -- CLI_DIRECC
                            '', -- CLI_CORREO
                            '', -- NRO_FACTV
                            GETDATE(), -- FECH_FACTV
                            ?, -- VENT_SUBTOTAL
                            ?, -- VENT_IGV
                            ?, -- VENT_TOTAL
                            'Venta Rápida', -- VENT_COMENT
                            1, -- MON_ID (moneda por defecto)
                            1, -- DOC_ID (documento por defecto)
                            GETDATE(), -- FECH_CREA
                            1  -- EST
                        )";
                
                // Calcular IGV y subtotal
                $total = $data['total'];
                $igv = $total * 0.18; // 18% de IGV
                $subtotal = $total - $igv;
    
                $stmt = $conectar->prepare($sql);
                $stmt->bindValue(1, $data['suc_id']);
                $stmt->bindValue(2, $data['usu_id']);
                $stmt->bindValue(3, $subtotal);
                $stmt->bindValue(4, $igv);
                $stmt->bindValue(5, $total);
                $stmt->execute();
                
                $venta_id = $conectar->lastInsertId();
                
                // Insertar detalle de venta
                foreach ($data['productos'] as $producto) {
                    $sql = "INSERT INTO TD_VENTA (
                                VENT_ID, 
                                PROD_ID, 
                                DETV_CANT, 
                                DETV_PRECIO
                            ) VALUES (?, ?, ?, ?)";
                    
                    $stmt = $conectar->prepare($sql);
                    $stmt->bindValue(1, $venta_id);
                    $stmt->bindValue(2, $producto['prod_id']);
                    $stmt->bindValue(3, $producto['cantidad']);
                    $stmt->bindValue(4, $producto['precio']);
                    $stmt->execute();
                    
                    // Actualizar stock
                    $sql = "UPDATE TM_PRODUCTO 
                            SET PROD_STOCK = PROD_STOCK - ?
                            WHERE PROD_ID = ?";

                    $stmt = $conectar->prepare($sql);
                    $stmt->bindValue(1, $producto['cantidad']);
                    $stmt->bindValue(2, $producto['prod_id']);
                    $stmt->execute();
                }
                
                return $venta_id;
                
            } catch (Exception $e) {
                throw new Exception("Error al registrar la venta: " . $e->getMessage());
            }
        }
    }
?>