<?php
require_once("../config/conexion.php");
require_once("../models/Venta.php");
require_once("../models/Caja.php");
require_once("../models/Cobro.php");

$venta = new Venta();
$caja = new Caja();
$cobro = new Cobro();

switch ($_GET["op"]) {
    case "registrar":
        $nro_factv = $_POST["nro_factv"];
        $fech_factv = $_POST["fech_factv"];
        $datos = $venta->insert_venta_x_suc_id($_POST["suc_id"], $_POST["usu_id"], $nro_factv, $fech_factv);
        foreach ($datos as $row) {
            $output["VENT_ID"] = $row["VENT_ID"];
        }
        echo json_encode($output);
        break;

    case "guardardetalle":
        $datos = $venta->insert_venta_detalle($_POST["vent_id"], $_POST["prod_id"], 
        $_POST["prod_pventa"], $_POST["detv_cant"]);
        break;

    case "eliminardetalle":
        $datos = $venta->delete_venta_detalle($_POST["detv_id"]);
        break;

    case "calculo":
        $datos = $venta->get_venta_calculo($_POST["vent_id"]);
        foreach ($datos as $row) {
            $output["VENT_SUBTOTAL"] = $row["VENT_SUBTOTAL"];
            $output["VENT_IGV"] = $row["VENT_IGV"];
            $output["VENT_TOTAL"] = $row["VENT_TOTAL"];
        }
        echo json_encode($output);
        break;

    case "listardetalle":
        $datos = $venta->get_venta_detalle($_POST["vent_id"]);
        $data = [];
        foreach ($datos as $row) {
            $sub_array = [];
            if ($row["PROD_IMG"] != '') {
                $sub_array[] = 
                    "<div class='d-flex align-items-center'>" .
                        "<div class='flex-shrink-0 me-2'>" .
                            "<img src='../../assets/Producto/".$row["PROD_IMG"]."' alt='' class='avatar-xs rounded-circle'>" .
                        "</div>" .
                    "</div>";
            } else {
                $sub_array[] = 
                    "<div class='d-flex align-items-center'>" .
                        "<div class='flex-shrink-0 me-2'>" .
                            "<img src='../../assets/Producto/no_imagen.png' alt='' class='avatar-xs rounded-circle'>" .
                        "</div>" .
                    "</div>";
            }
            $sub_array[] = $row["CAT_NOM"];
            $sub_array[] = $row["PROD_NOM"];
            $sub_array[] = $row["UND_NOM"];
            $sub_array[] = $row["PROD_PVENTA"];
            $sub_array[] = $row["DETV_CANT"];
            $sub_array[] = $row["DETV_TOTAL"];
            $sub_array[] = '<button type="button" onClick="eliminar('.$row["DETV_ID"].','.$row["VENT_ID"].')" id="'.$row["DETV_ID"].'" class="btn btn-danger btn-icon waves-effect waves-light"><i class="ri-delete-bin-5-line"></i></button>';
            $data[] = $sub_array;
        }
        $results = [
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        ];
        echo json_encode($results);
        break;

    case "listardetalleformato":
        $datos = $venta->get_venta_detalle($_POST["vent_id"]);
        foreach ($datos as $row) {
            ?>
            <tr>
                <td>
                    <?php 
                    if ($row["PROD_IMG"] != '') {
                        echo "<div class='d-flex align-items-center'>" .
                                "<div class='flex-shrink-0 me-2'>" .
                                    "<img src='../../assets/producto/".$row["PROD_IMG"]."' alt='' class='avatar-xs rounded-circle'>" .
                                "</div>" .
                             "</div>";
                    } else {
                        echo "<div class='d-flex align-items-center'>" .
                                "<div class='flex-shrink-0 me-2'>" .
                                    "<img src='../../assets/producto/no_imagen.png' alt='' class='avatar-xs rounded-circle'>" .
                                "</div>" .
                             "</div>";
                    }
                    ?>
                </td>
                <td><?php echo $row["CAT_NOM"]; ?></td>
                <td><?php echo $row["PROD_NOM"]; ?></td>
                <td scope="row"><?php echo $row["UND_NOM"]; ?></td>
                <td><?php echo $row["PROD_PVENTA"]; ?></td>
                <td><?php echo $row["DETV_CANT"]; ?></td>
                <td class="text-end"><?php echo $row["DETV_TOTAL"]; ?></td>
            </tr>
            <?php
        }
        break;

    case "guardar":
        try {
            $nro_factv = $_POST["nro_factv"];
            $fech_factv = $_POST["fech_factv"];
            $pag_id = (int)$_POST["pag_id"];
            $cobro_pagado = $pag_id == 2 ? (int)$_POST["cobro_pagado"] : 0;

            if (empty($nro_factv) || empty($fech_factv) || $_POST["doc_id"] == "0" || $pag_id == "0" || $_POST["cli_id"] == "0" || $_POST["mon_id"] == "0" || empty($_POST["caj_id"])) {
                throw new Exception("Campos básicos vacíos o caja no definida");
            }

            $calculo = $venta->get_venta_calculo($_POST["vent_id"]);
            if (!$calculo || $calculo[0]["VENT_TOTAL"] == null) {
                throw new Exception("No hay detalles de venta");
            }

            $success = $venta->update_venta(
                $_POST["vent_id"], $pag_id, $_POST["cli_id"],
                $_POST["cli_ruc"], $_POST["cli_direcc"], $_POST["cli_correo"],
                $_POST["vent_coment"], $_POST["mon_id"], $_POST["doc_id"],
                $nro_factv, $fech_factv, $_POST["caj_id"]
            );

            if (!$success) {
                throw new Exception("Error al actualizar la venta en TM_VENTA");
            }

            if ($pag_id == 2) {
                $vent_total = (int)$calculo[0]["VENT_TOTAL"];
                if ($cobro_pagado > $vent_total) {
                    throw new Exception("El pago inicial no puede exceder el total");
                }

                $cobro_id = $cobro->registrar_venta_credito(
                    $_POST["vent_id"], $_POST["cli_id"], $_POST["caj_id"],
                    $vent_total, $cobro_pagado
                );
                echo json_encode([
                    "success" => true,
                    "message" => "Venta a crédito registrada con Nro: V-" . $_POST["vent_id"],
                    "VENT_ID" => $_POST["vent_id"],
                    "COBRO_ID" => $cobro_id,
                    "redirect" => "../mntcobro/"
                ]);
            } else {
                echo json_encode([
                    "success" => true,
                    "message" => "Venta registrada con Nro: V-" . $_POST["vent_id"],
                    "VENT_ID" => $_POST["vent_id"]
                ]);
            }
        } catch (Exception $e) {
            echo json_encode([
                "success" => false,
                "message" => "Error al procesar la venta: " . $e->getMessage()
            ]);
        }
        break;

    case "mostrar":
        $datos = $venta->get_venta($_POST["vent_id"]);
        foreach ($datos as $row) {
            $output["VENT_ID"] = $row["VENT_ID"];
            $output["SUC_ID"] = $row["SUC_ID"];
            $output["PAG_ID"] = $row["PAG_ID"];
            $output["PAG_NOM"] = $row["PAG_NOM"];
            $output["CLI_ID"] = $row["CLI_ID"];
            $output["CLI_RUC"] = $row["CLI_RUC"];
            $output["CLI_DIRECC"] = $row["CLI_DIRECC"];
            $output["CLI_CORREO"] = $row["CLI_CORREO"];
            $output["VENT_SUBTOTAL"] = $row["VENT_SUBTOTAL"];
            $output["VENT_IGV"] = $row["VENT_IGV"];
            $output["VENT_TOTAL"] = $row["VENT_TOTAL"];
            $output["VENT_COMENT"] = $row["VENT_COMENT"];
            $output["USU_ID"] = $row["USU_ID"];
            $output["USU_NOM"] = $row["USU_NOM"];
            $output["USU_APE"] = $row["USU_APE"];
            $output["USU_TELF"] = $row["USU_TELF"];
            $output["USU_CORREO"] = $row["USU_CORREO"];
            $output["MON_ID"] = $row["MON_ID"];
            $output["MON_NOM"] = $row["MON_NOM"];
            $output["FECH_CREA"] = $row["FECH_CREA"];
            $output["SUC_NOM"] = $row["SUC_NOM"];
            $output["EMP_NOM"] = $row["EMP_NOM"];
            $output["EMP_RUC"] = $row["EMP_RUC"];
            $output["EMP_CORREO"] = $row["EMP_CORREO"];
            $output["EMP_TELF"] = $row["EMP_TELF"];
            $output["EMP_DIRECC"] = $row["EMP_DIRECC"];
            $output["EMP_PAG"] = $row["EMP_PAG"];
            $output["COM_NOM"] = $row["COM_NOM"];
            $output["ROL_NOM"] = $row["ROL_NOM"];
            $output["CLI_NOM"] = $row["CLI_NOM"];
            $output["NRO_FACTV"] = $row["NRO_FACTV"];
            $output["FECH_FACTV"] = $row["FECH_FACTV"];
        }
        echo json_encode($output);
        break;

    case "listarventa":
        $datos = $venta->get_venta_listado($_POST["suc_id"]);
        $data = [];
        foreach ($datos as $row) {
            $sub_array = [];
            $sub_array[] = "V-" . $row["VENT_ID"];
            $sub_array[] = $row["DOC_NOM"];
            $sub_array[] = $row["CLI_RUC"];
            $sub_array[] = $row["NRO_FACTV"];
            $sub_array[] = $row["CLI_NOM"];
            $sub_array[] = $row["PAG_NOM"];
            $sub_array[] = $row["MON_NOM"];
            $sub_array[] = $row["VENT_SUBTOTAL"];
            $sub_array[] = $row["VENT_TOTAL"];
            $sub_array[] = $row["USU_NOM"] . " " . $row["USU_APE"];
            if ($row["USU_IMG"] != '') {
                $sub_array[] =
                    "<div class='d-flex align-items-center'>" .
                        "<div class='flex-shrink-0 me-2'>" .
                            "<img src='../../assets/usuario/".$row["USU_IMG"]."' alt='' class='avatar-xs rounded-circle'>" .
                        "</div>" .
                    "</div>";
            } else {
                $sub_array[] =
                    "<div class='d-flex align-items-center'>" .
                        "<div class='flex-shrink-0 me-2'>" .
                            "<img src='../../assets/usuario/no_imagen.png' alt='' class='avatar-xs rounded-circle'>" .
                        "</div>" .
                    "</div>";
            }
            $sub_array[] = '<a href="../ViewVenta/?v='.$row["VENT_ID"].'" target="_blank" class="btn btn-primary btn-icon waves-effect waves-light"><i class="ri-printer-line"></i></a>';
            $sub_array[] = '<button type="button" onClick="ver('.$row["VENT_ID"].')" id="'.$row["VENT_ID"].'" class="btn btn-secondary btn-icon waves-effect waves-light"><i class="ri-settings-2-line"></i></button>';
            $data[] = $sub_array;
        }
        $results = [
            "sEcho" => 1,
            "iTotalRecords" => count($data),
            "iTotalDisplayRecords" => count($data),
            "aaData" => $data
        ];
        echo json_encode($results);
        break;

    case "listartopproducto":
        $datos = $venta->get_venta_top_productos($_POST["suc_id"]);
        foreach ($datos as $row) {
            ?>
            <tr>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="avatar-sm bg-light rounded p-1 me-2">
                            <?php 
                            if ($row["PROD_IMG"] != '') {
                                echo "<img src='../../assets/producto/".$row["PROD_IMG"]."' alt='' class='img-fluid d-block' />";
                            } else {
                                echo "<img src='../../assets/producto/no_imagen.png' alt='' class='img-fluid d-block' />";
                            }
                            ?>
                        </div>
                        <div>
                            <h5 class="fs-14 my-1"><?php echo $row["PROD_NOM"]; ?></h5>
                            <span class="text-muted"><?php echo $row["CAT_NOM"]; ?></span>
                        </div>
                    </div>
                </td>
                <td>
                    <h5 class="fs-14 my-1 fw-normal"><?php echo $row["PROD_PVENTA"]; ?></h5>
                    <span class="text-muted">P.Venta</span>
                </td>
                <td>
                    <h5 class="fs-14 my-1 fw-normal"><?php echo $row["CANT"]; ?></h5>
                    <span class="text-muted">Cant</span>
                </td>
                <td>
                    <h5 class="fs-14 my-1 fw-normal"><?php echo $row["PROD_STOCK"]; ?></h5>
                    <span class="text-muted">Stock</span>
                </td>
                <td>
                    <h5 class="fs-14 my-1 fw-normal"><b><?php echo $row["MON_NOM"]; ?></b> <?php echo $row["TOTAL"]; ?></h5>
                    <span class="text-muted">Total</span>
                </td>
            </tr>
            <?php
        }
        break;

    case "barras":
        $datos = $venta->get_venta_barras($_POST["suc_id"]);
        $data = [];
        foreach ($datos as $row) {
            $data[] = $row;
        }
        echo json_encode($data);
        break;

    case "verificarcaja":
        $suc_id = $_POST["suc_id"];
        $datos = $caja->verificar_caja($suc_id);
        if ($datos == 0) {
            echo json_encode([
                "status" => "success",
                "message" => "No hay caja abierta. Puedes proceder a abrir una nueva caja."
            ]);
        }
        break;
}
?>