<?php
    // TODO: Llamando Clases
    require_once("../config/conexion.php");
    require_once("../models/PagoCuota.php");
    
    // TODO: Iniciando Clase
    $pagoCuota = new PagoCuota();

    switch($_GET["op"]){
        // TODO: Guardar y editar una cuota
        case "guardaryeditar":
            if(empty($_POST["cuota_id"])){
                $pagoCuota->insert_pago_cuota($_POST["compr_id"], $_POST["prov_id"], 
                $_POST["monto_total"], $_POST["fecha_vencimiento"]);
            }else{
                $pagoCuota->update_pago_cuota($_POST["cuota_id"], $_POST["cuota_pagada"]);
            }
            break;

        // TODO: Listado de cuotas en formato JSON para DataTables
        case "listar":
            $datos = $pagoCuota->get_pago_cuotas();
            $data = Array();
            foreach($datos as $row){
                $sub_array = array();
                $sub_array[] = $row["PROV_NOM"];
                $sub_array[] = $row["COMPR_TOTAL"];
                $sub_array[] = $row["CUOTA_PAGADA"];
                $sub_array[] = $row["CUOTA_PENDIENTE"];
                $sub_array[] = $row["FECHA_INICIO"];
                $sub_array[] = $row["FECHA_VENCIMIENTO"];
                $sub_array[] = '<button type="button" onClick="editar('.$row["CUOTA_ID"].')" id="'.$row["CUOTA_ID"].'" class="btn btn-warning btn-icon waves-effect waves-light"><i class="ri-edit-2-line"></i></button>';
                $sub_array[] = '<button type="button" onClick="eliminar('.$row["CUOTA_ID"].')" id="'.$row["CUOTA_ID"].'" class="btn btn-danger btn-icon waves-effect waves-light"><i class="ri-delete-bin-5-line"></i></button>';
                $data[] = $sub_array;
            }
            
            $results = array(
                "sEcho"=>1,
                "iTotalRecords"=>count($data),
                "iTotalDisplayRecords"=>count($data),
                "aaData"=>$data);
            echo json_encode($results);
            break;

        // TODO: Mostrar información de una cuota específica
        case "mostrar":
            $datos = $pagoCuota->get_pago_cuota_proveedor_x_id($_POST["cuota_id"]);
            if(is_array($datos) && count($datos) > 0){
                foreach ($datos as $row) {
                    $output["CUOTA_ID"] = $row["CUOTA_ID"];
                    $output["PROV_ID"]  = $row["PROV_ID"];
                    $output["PROV_NOM"]  = $row["PROV_NOM"];
                    $output["COMPR_TOTAL"]  = $row["COMPR_TOTAL"];
                    $output["CUOTA_PAGADA"]  = $row["CUOTA_PAGADA"];
                    $output["CUOTA_PENDIENTE"]  = $row["CUOTA_PENDIENTE"];
                    $output["FECHA_VENCIMIENTO"]  = $row["FECHA_VENCIMIENTO"];
                }
                echo json_encode($datos);
            }
            break;

        // TODO: Eliminar una cuota
        case "eliminar":
            $pagoCuota->delete_pago_cuota($_POST["cuota_id"]);
            break;
    }
?>