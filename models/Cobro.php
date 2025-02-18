<?php
    class Cobro extends Conectar {
        /* TODO: Listar Registros */
        public function get_cobro($suc_id) {
            $conectar = parent::Conexion();
            $sql = "SELECT 
                        c.*,
                        v.VENT_ID,
                        cl.CLI_NOM,
                        p.PAG_NOM,
                        m.MON_NOM
                    FROM TM_COBRO c
                    INNER JOIN TM_VENTA v ON c.VENTA_ID = v.VENT_ID
                    INNER JOIN TM_CLIENTE cl ON v.CLI_ID = cl.CLI_ID
                    INNER JOIN TM_PAGO p ON c.PAG_ID = p.PAG_ID
                    INNER JOIN TM_MONEDA m ON c.MON_ID = m.MON_ID
                    WHERE c.SUC_ID = ?";
            
            $sql = $conectar->prepare($sql);
            $sql->bindValue(1, $suc_id);
            $sql->execute();
            return $sql->fetchAll(PDO::FETCH_ASSOC);
        }

        /* TODO: Registrar Cobro */
        public function insert_cobro($caja_id, $suc_id, $usu_id, $venta_id, $cli_id, 
                                   $cobro_monto, $pag_id, $mon_id, $cobro_comentario) {
            $conectar = parent::Conexion();
            $sql = "SP_I_COBRO_01 ?,?,?,?,?,?,?,?,?";
            $query = $conectar->prepare($sql);
            $query->bindValue(1, $caja_id);
            $query->bindValue(2, $suc_id);
            $query->bindValue(3, $usu_id);
            $query->bindValue(4, $venta_id);
            $query->bindValue(5, $cli_id);
            $query->bindValue(6, $cobro_monto);
            $query->bindValue(7, $pag_id);
            $query->bindValue(8, $mon_id);
            $query->bindValue(9, $cobro_comentario);
            $query->execute();
        }

        /* TODO: Obtener registro por ID */
        public function get_cobro_x_id($cobro_id) {
            $conectar = parent::Conexion();
            $sql = "SELECT * FROM TM_COBRO WHERE COBRO_ID = ? AND EST = 1";
            $query = $conectar->prepare($sql);
            $query->bindValue(1, $cobro_id);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        /* TODO: Anular Cobro */
        public function delete_cobro($cobro_id) {
            $conectar = parent::Conexion();
            try {
                $conectar->beginTransaction();

                // Obtener información del cobro
                $sql = "SELECT CAJA_ID, COBRO_MONTO FROM TM_COBRO WHERE COBRO_ID = ?";
                $query = $conectar->prepare($sql);
                $query->bindValue(1, $cobro_id);
                $query->execute();
                $cobro = $query->fetch(PDO::FETCH_ASSOC);

                // Actualizar caja (restar el monto)
                $sql = "UPDATE TM_CAJA 
                       SET CAJA_INGRESO = CAJA_INGRESO - ?,
                           CAJA_SALDO = CAJA_SALDO - ?
                       WHERE CAJA_ID = ?";
                $query = $conectar->prepare($sql);
                $query->bindValue(1, $cobro['COBRO_MONTO']);
                $query->bindValue(2, $cobro['COBRO_MONTO']);
                $query->bindValue(3, $cobro['CAJA_ID']);
                $query->execute();

                // Anular cobro
                $sql = "UPDATE TM_COBRO 
                       SET EST = 0,
                           FECH_CREA = GETDATE()
                       WHERE COBRO_ID = ?";
                $query = $conectar->prepare($sql);
                $query->bindValue(1, $cobro_id);
                $query->execute();

                $conectar->commit();
                return true;
            } catch (Exception $e) {
                $conectar->rollBack();
                return false;
            }
        }
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
                    WHERE c.CAJA_ID = ?
                    UNION ALL
                    SELECT 
                        'E' as TIPO,
                        g.GASTO_ID as ID,
                        'Gasto #' + CAST(g.GASTO_ID as VARCHAR) as REFERENCIA,
                        g.GASTO_FECHA as FECHA,
                        p.PAG_NOM,
                        m.MON_NOM,
                        g.GASTO_MONTO as MONTO,
                        g.GASTO_COMENTARIO as COMENTARIO,
                        g.EST
                    FROM TM_GASTO g
                    INNER JOIN TM_PAGO p ON g.PAG_ID = p.PAG_ID
                    INNER JOIN TM_MONEDA m ON g.MON_ID = m.MON_ID
                    WHERE g.CAJA_ID = ?
                    ORDER BY FECHA DESC";
            
            $query = $conectar->prepare($sql);
            $query->bindValue(1, $caja_id);
            $query->bindValue(2, $caja_id);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
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

        public function get_cobro_detalle($cobro_id) {
            $conectar = parent::Conexion();
            $sql = "SELECT 
                        c.*,
                        cl.CLI_NOM,
                        p.PAG_NOM,
                        m.MON_NOM
                    FROM TM_COBRO c
                    INNER JOIN TM_CLIENTE cl ON c.CLI_ID = cl.CLI_ID
                    INNER JOIN TM_PAGO p ON c.PAG_ID = p.PAG_ID
                    INNER JOIN TM_MONEDA m ON c.MON_ID = m.MON_ID
                    WHERE c.COBRO_ID = ? AND c.EST = 1";
            
            $query = $conectar->prepare($sql);
            $query->bindValue(1, $cobro_id);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }
    }
    
?>