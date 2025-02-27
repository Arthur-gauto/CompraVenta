<?php
require_once("../config/conexion.php");
require_once("../models/Cobro.php");

$cobro = new Cobro();

switch ($_GET["op"]) {
    case "listar_pendientes":
        $cli_id = isset($_POST["cli_id"]) && !empty($_POST["cli_id"]) ? $_POST["cli_id"] : null;
        $datos = $cobro->get_ventas_pendientes($cli_id ? ["CLI_ID" => $cli_id] : []);
        $data = [];
        foreach ($datos as $row) {
            $data[] = [
                "VENT_ID" => $row["VENT_ID"],
                "CLI_NOM" => $row["CLI_NOM"],
                "VENT_TOTAL" => $row["VENT_TOTAL"],
                "TotalPagado" => $row["TotalPagado"],
                "SaldoPendiente" => $row["SaldoPendiente"],
                "FECH_FACTV" => $row["FECH_FACTV"]
            ];
        }
        $results = [
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        ];
        echo json_encode($results);
        break;

    case "listar":
        $vent_id = isset($_POST["vent_id"]) && !empty($_POST["vent_id"]) ? $_POST["vent_id"] : null;
        if (!$vent_id) {
            $results = [
                "sEcho" => 1,
                "iTotalRecords" => 0,
                "iTotalDisplayRecords" => 0,
                "aaData" => [],
                "resumen" => ["VENT_TOTAL" => 0, "TotalPagado" => 0, "SaldoPendiente" => 0, "Estado" => "Pendiente"]
            ];
            echo json_encode($results);
            break;
        }
        $datos = $cobro->get_historial_por_vent_id($vent_id);
        $data = [];
        foreach ($datos as $row) {
            $data[] = [
                "COBRO_ID" => $row["COBRO_ID"],
                "COBRO_PAGADO" => $row["COBRO_PAGADO"],
                "FECH_CREA" => $row["FECH_CREA"],
                "EST" => $row["EST"]
            ];
        }
        $resumen = $cobro->get_resumen_venta($vent_id);
        $results = [
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data,
            "resumen" => $resumen
        ];
        echo json_encode($results);
        break;

    case "registrar_pago":
        try {
            $vent_id = $_POST["vent_id"];
            $cli_id = $_POST["cli_id"];
            $caj_id = $_POST["caj_id"];
            $cobro_pagado = isset($_POST["cobro_pagado"]) ? (int)$_POST["cobro_pagado"] : 0;

            if ($cobro_pagado <= 0) {
                throw new Exception("El monto a pagar debe ser mayor a 0.");
            }

            $cobro_id = $cobro->registrar_pago($vent_id, $cli_id, $caj_id, $cobro_pagado);
            echo json_encode(["success" => true, "message" => "Pago registrado con éxito", "COBRO_ID" => $cobro_id]);
        } catch (Exception $e) {
            echo json_encode(["success" => false, "message" => $e->getMessage()]);
        }
        break;

    case "mostrar":
        try {
            $vent_id = $_POST["vent_id"];
            if (empty($vent_id)) {
                throw new Exception("ID de venta no proporcionado");
            }
            $data = $cobro->get_cobro_por_vent_id($vent_id);
            if (!$data) {
                throw new Exception("No se encontraron datos para VENT_ID: " . $vent_id);
            }
            echo json_encode($data);
        } catch (Exception $e) {
            echo json_encode([
                "success" => false,
                "message" => "Error: " . $e->getMessage()
            ]);
        }
        break;
}
?>