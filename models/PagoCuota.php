<?php
    class PagoCuota extends Conectar {

        // TODO: LISTAR TODAS LAS CUOTAS
        public function get_pago_cuotas() {
            $conectar = parent::Conexion();
            $sql = "SP_L_PAGO_CUOTA_01";
            $query = $conectar->prepare($sql);
            $query->execute();
            return $query->fetchAll(PDO::FETCH_ASSOC);
        }

        // TODO: LISTAR CUOTA POR ID
        public function get_pago_cuota_x_id($cuota_id) {
            $conectar = parent::Conexion();
            $sql = "SP_L_PAGO_CUOTA_02 ?";
            $query = $conectar->prepare($sql);
            $query->bindValue(1, $cuota_id);
            $query->execute();
            return $query->fetch(PDO::FETCH_ASSOC);
        }

        public function get_pago_cuota_proveedor_x_id($cuota_id) {
            $conectar = parent::Conexion();
            $sql = "SP_L_PAGO_CUOTA_03 ?";
            $query = $conectar->prepare($sql);
            $query->bindValue(1, $cuota_id);
            $query->execute();
            return $query->fetch(PDO::FETCH_ASSOC);
        }

        // TODO: REGISTRAR UNA NUEVA CUOTA
        public function insert_pago_cuota($compr_id, $prov_id, $monto_total, $fecha_vencimiento) {
            $conectar = parent::Conexion();
            $sql = "SP_I_PAGO_CUOTA_01 ?, ?, ?, ?";
            $query = $conectar->prepare($sql);
            $query->bindValue(1, $compr_id);
            $query->bindValue(2, $prov_id);
            $query->bindValue(3, $monto_total);
            $query->bindValue(4, $fecha_vencimiento);
            $query->execute();
        }

        // TODO: REGISTRAR UN PAGO A UNA CUOTA
        public function update_pago_cuota($cuota_id, $monto) {
            $conectar = parent::Conexion();
            $sql = "SP_U_PAGO_CUOTA_01 ?, ?";
            $query = $conectar->prepare($sql);
            $query->bindValue(1, $cuota_id);
            $query->bindValue(2, $monto);
            $query->execute();
        }

        // TODO: ELIMINAR UNA CUOTA
        public function delete_pago_cuota($cuota_id) {
            $conectar = parent::Conexion();
            $sql = "SP_D_PAGO_CUOTA_01 ?";
            $query = $conectar->prepare($sql);
            $query->bindValue(1, $cuota_id);
            $query->execute();
        }
    }
?>