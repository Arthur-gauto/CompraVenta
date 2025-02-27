<?php
class Cobro extends Conectar {

    // Registrar una venta a crédito con pago inicial
    public function registrar_venta_credito($vent_id, $cli_id, $caj_id, $vent_total, $cobro_pagado) {
        $conectar = parent::Conexion();
        $sql = "sp_RegistrarVentaCredito ?, ?, ?, ?, ?, ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $vent_id, PDO::PARAM_INT);
        $query->bindValue(2, $cli_id, PDO::PARAM_INT);
        $query->bindValue(3, $caj_id, PDO::PARAM_INT);
        $query->bindValue(4, $vent_total, PDO::PARAM_INT);
        $query->bindValue(5, $cobro_pagado, PDO::PARAM_INT);
        $query->bindParam(6, $cobro_id, PDO::PARAM_INT | PDO::PARAM_INPUT_OUTPUT, 10);
        $query->execute();
        return $cobro_id;
    }

    // Registrar un pago adicional
    public function registrar_pago($vent_id, $cli_id, $caj_id, $cobro_pagado) {
        $conectar = parent::Conexion();
        $sql = "sp_RegistrarPago ?, ?, ?, ?, ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $vent_id, PDO::PARAM_INT);
        $query->bindValue(2, $cli_id, PDO::PARAM_INT);
        $query->bindValue(3, $caj_id, PDO::PARAM_INT);
        $query->bindValue(4, $cobro_pagado, PDO::PARAM_INT);
        $query->bindParam(5, $cobro_id, PDO::PARAM_INT | PDO::PARAM_INPUT_OUTPUT, 10);
        $query->execute();
        return $cobro_id;
    }

    // Consultar el historial de pagos por VENT_ID
    public function get_historial_por_vent_id($vent_id) {
        $conectar = parent::Conexion();
        $sql = "SELECT COBRO_ID, COBRO_PAGADO, FECH_CREA,
                       CASE 
                           WHEN VENT_TOTAL = (SELECT SUM(COBRO_PAGADO) FROM TM_COBRO WHERE VENT_ID = ?) THEN 'Pagado'
                           WHEN (SELECT SUM(COBRO_PAGADO) FROM TM_COBRO WHERE VENT_ID = ?) > 0 THEN 'Parcial'
                           ELSE 'Pendiente'
                       END AS EST
                FROM TM_COBRO
                WHERE VENT_ID = ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $vent_id, PDO::PARAM_INT);
        $query->bindValue(2, $vent_id, PDO::PARAM_INT);
        $query->bindValue(3, $vent_id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener ventas pendientes
    public function get_ventas_pendientes($filtros = []) {
        $conectar = parent::Conexion();
        $sql = "SELECT TM_COBRO.VENT_ID, TM_COBRO.CLI_ID, TM_CLIENTE.CLI_NOM, TM_COBRO.VENT_TOTAL, 
                       SUM(TM_COBRO.COBRO_PAGADO) AS TotalPagado, 
                       (TM_COBRO.VENT_TOTAL - SUM(TM_COBRO.COBRO_PAGADO)) AS SaldoPendiente,
                       TM_VENTA.FECH_FACTV
                FROM TM_COBRO
                JOIN TM_CLIENTE ON TM_COBRO.CLI_ID = TM_CLIENTE.CLI_ID
                JOIN TM_VENTA ON TM_COBRO.VENT_ID = TM_VENTA.VENT_ID
                WHERE 1=1";
        if (isset($filtros["CLI_ID"])) {
            $sql .= " AND TM_COBRO.CLI_ID = ?";
        }
        $sql .= " GROUP BY TM_COBRO.VENT_ID, TM_COBRO.CLI_ID, TM_CLIENTE.CLI_NOM, TM_COBRO.VENT_TOTAL, TM_VENTA.FECH_FACTV
                  HAVING TM_COBRO.VENT_TOTAL > SUM(TM_COBRO.COBRO_PAGADO)";
        $query = $conectar->prepare($sql);
        if (isset($filtros["CLI_ID"])) {
            $query->bindValue(1, $filtros["CLI_ID"], PDO::PARAM_INT);
        }
        $query->execute();
        return $query->fetchAll(PDO::FETCH_ASSOC);
    }

    // Obtener resumen de una venta
    public function get_resumen_venta($vent_id) {
        $conectar = parent::Conexion();
        $sql = "SELECT VENT_TOTAL, SUM(COBRO_PAGADO) AS TotalPagado, 
                       (VENT_TOTAL - SUM(COBRO_PAGADO)) AS SaldoPendiente,
                       CASE 
                           WHEN VENT_TOTAL = SUM(COBRO_PAGADO) THEN 'Pagado'
                           WHEN SUM(COBRO_PAGADO) > 0 THEN 'Parcial'
                           ELSE 'Pendiente'
                       END AS Estado
                FROM TM_COBRO
                WHERE VENT_ID = ?
                GROUP BY VENT_ID, VENT_TOTAL";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $vent_id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC) ?: ["VENT_TOTAL" => 0, "TotalPagado" => 0, "SaldoPendiente" => 0, "Estado" => "Pendiente"];
    }

    public function get_cobro_por_vent_id($vent_id) {
        $conectar = parent::Conexion();
        $sql = "SELECT TOP 1 VENT_ID, CLI_ID FROM TM_COBRO WHERE VENT_ID = ?";
        $query = $conectar->prepare($sql);
        $query->bindValue(1, $vent_id, PDO::PARAM_INT);
        $query->execute();
        return $query->fetch(PDO::FETCH_ASSOC);
    }

}
?>