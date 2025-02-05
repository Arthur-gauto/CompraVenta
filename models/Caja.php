<?php
    class Caja extends Conectar {
        /* Métodos existentes se mantienen igual */

        public function get_caja_detalle($caja_id) {
            $conectar = parent::Conexion();
            $sql = "SELECT 
                        'I' as TIPO,
                        c.COBRO_ID as ID,
                        'Cobro #' + CAST(c.COBRO_ID as VARCHAR) as REFERENCIA,
                        c.COBRO_FECHA as FECHA,
                        p.PAG_NOM,
                        m.MON_NOM,
                        c.COBRO_MONTO as MONTO,
                        c.COBRO_COMENTARIO as COMENTARIO,
                        c.EST
                    FROM TM_COBRO c
                    INNER JOIN TM_PAGO p ON c.PAG_ID = p.PAG_ID
                    INNER JOIN TM_MONEDA m ON c.MON_ID = m.MON_ID
                    WHERE c.CAJA_ID = ?";
            
            $sql = $conectar->prepare($sql);
            $sql->bindValue(1, $caja_id);
            $sql->execute();
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        }
        public function get_caja_x_id_detalle($caja_id) {
            $conectar = parent::Conexion();
            $sql = "SELECT 
                        c.*,
                        s.SUC_NOM,
                        u.USU_NOM
                    FROM TM_CAJA c
                    INNER JOIN TM_SUCURSAL s ON c.SUC_ID = s.SUC_ID
                    INNER JOIN TM_USUARIO u ON c.USU_ID = u.USU_ID
                    WHERE c.CAJA_ID = ? AND c.EST = 1";
            
            $query = $conectar->prepare($sql);
            $query->bindValue(1, $caja_id);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        public function get_caja($suc_id) {
            $conectar = parent::Conexion();
            $sql = "SELECT 
                        c.*,
                        s.SUC_NOM,
                        u.USU_NOM
                    FROM TM_CAJA c
                    INNER JOIN TM_SUCURSAL s ON c.SUC_ID = s.SUC_ID
                    INNER JOIN TM_USUARIO u ON c.USU_ID = u.USU_ID
                    WHERE c.SUC_ID = ?";
            $sql = $conectar->prepare($sql);
            $sql->bindValue(1, $suc_id);
            $sql->execute();
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        }

        public function insert_caja($suc_id, $usu_id, $caja_fecha) {
            try {
                $conectar = parent::Conexion();
                
                // Log de conexión
                error_log("Conexión establecida");
                
                $sql = "INSERT INTO TM_CAJA (SUC_ID, USU_ID, CAJA_FECHA, CAJA_INGRESO, CAJA_EGRESO, CAJA_SALDO, FECH_CREA, EST) 
                        VALUES (?, ?, ?, 0, 0, 0, GETDATE(), 1)";
                
                // Log de query
                error_log("Query: " . $sql);
                
                $stmt = $conectar->prepare($sql);
                $stmt->bindValue(1, $suc_id);
                $stmt->bindValue(2, $usu_id);
                $stmt->bindValue(3, $caja_fecha);
                
                // Ejecutar y obtener el ID insertado
                $stmt->execute();
                $caja_id = $conectar->lastInsertId();
                
                // Log del resultado
                error_log("Caja creada con ID: " . $caja_id);
                
                return ["CAJA_ID" => $caja_id];
            } catch (Exception $e) {
                error_log("Error en insert_caja: " . $e->getMessage());
                throw $e;
            }
        }

        public function get_caja_x_id($caja_id) {
            $conectar = parent::Conexion();
            $sql = "SELECT 
                        c.*,
                        s.SUC_NOM,
                        u.USU_NOM
                    FROM TM_CAJA c
                    INNER JOIN TM_SUCURSAL s ON c.SUC_ID = s.SUC_ID
                    INNER JOIN TM_USUARIO u ON c.USU_ID = u.USU_ID
                    WHERE c.CAJA_ID = ? AND c.EST = 1";
            $sql = $conectar->prepare($sql);
            $sql->bindValue(1, $caja_id);
            $sql->execute();
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        }

        public function delete_caja($caja_id) {
            try {
                $conectar = parent::Conexion();
                $sql = "UPDATE TM_CAJA 
                       SET EST = 0,
                           FECH_CREA = GETDATE()
                       WHERE CAJA_ID = ?";
                $sql = $conectar->prepare($sql);
                $sql->bindValue(1, $caja_id);
                return $sql->execute();
            } catch (Exception $e) {
                error_log("Error en delete_caja: " . $e->getMessage());
                throw $e;
            }
        }
        
        public function get_caja_abierta($suc_id) {
            $conectar = parent::Conexion();
            $sql = "SELECT TOP 1 * FROM TM_CAJA 
                    WHERE SUC_ID = ? AND EST = 1 
                    ORDER BY CAJA_ID DESC";
            
            $stmt = $conectar->prepare($sql);
            $stmt->bindValue(1, $suc_id);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        
        public function registrar_ingreso($caja_id, $venta_id, $monto, $comentario) {
            $conectar = parent::Conexion();
            $sql = "UPDATE TM_CAJA 
                    SET CAJA_INGRESO = CAJA_INGRESO + ?,
                        CAJA_SALDO = CAJA_SALDO + ?
                    WHERE CAJA_ID = ?";
            
            $stmt = $conectar->prepare($sql);
            $stmt->bindValue(1, $monto);
            $stmt->bindValue(2, $monto);
            $stmt->bindValue(3, $caja_id);
            return $stmt->execute();
        }
    }
    
?>