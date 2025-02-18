<?php
    require_once("../config/conexion.php");
    require_once("../models/Cobro.php");
    $cobro = new Cobro();

    switch($_GET["op"]){
        /* Cases existentes se mantienen igual */

        case "mostrar_detalle":
            $datos=$cobro->get_cobro_detalle($_POST["cobro_id"]);  
            if(is_array($datos)==true and count($datos)>0){
                foreach($datos as $row){
                    $output["COBRO_ID"] = $row["COBRO_ID"];
                    $output["VENTA_ID"] = $row["VENTA_ID"];
                    $output["CLI_ID"] = $row["CLI_ID"];
                    $output["CLI_NOM"] = $row["CLI_NOM"];
                    $output["COBRO_FECHA"] = $row["COBRO_FECHA"];
                    $output["PAG_NOM"] = $row["PAG_NOM"];
                    $output["MON_NOM"] = $row["MON_NOM"];
                    $output["COBRO_MONTO"] = $row["COBRO_MONTO"];
                    $output["COBRO_COMENTARIO"] = $row["COBRO_COMENTARIO"];
                }
                echo json_encode($output);
            }
            break;

        case "listar":
            try {
                $datos = $cobro->get_cobro($_POST["suc_id"]);
                $data = Array();
                foreach($datos as $row){
                    $sub_array = array();
                    $sub_array[] = $row["COBRO_ID"];
                    $sub_array[] = $row["VENTA_ID"];
                    $sub_array[] = $row["CLI_NOM"];
                    $sub_array[] = $row["COBRO_FECHA"];
                    $sub_array[] = $row["PAG_NOM"];
                    $sub_array[] = $row["MON_NOM"];
                    $sub_array[] = $row["COBRO_MONTO"];
                    $sub_array[] = $row["COBRO_COMENTARIO"];
                    
                    if ($row["EST"]==1){
                        $sub_array[] = '<span class="badge bg-success">ACTIVO</span>';
                    }else{
                        $sub_array[] = '<span class="badge bg-danger">ANULADO</span>';
                    }

                    $sub_array[] = '<button type="button" onClick="ver('.$row["COBRO_ID"].')" id="'.$row["COBRO_ID"].'" class="btn btn-info btn-icon waves-effect waves-light"><i class="ri-eye-fill"></i></button>';
                    $sub_array[] = '<button type="button" onClick="eliminar('.$row["COBRO_ID"].')" id="'.$row["COBRO_ID"].'" class="btn btn-danger btn-icon waves-effect waves-light"><i class="ri-delete-bin-5-line"></i></button>';
                    $data[] = $sub_array;
                }

                $results = array(
                    "sEcho"=>1,
                    "iTotalRecords"=>count($data),
                    "iTotalDisplayRecords"=>count($data),
                    "aaData"=>$data);
                echo json_encode($results);
            } catch (Exception $e) {
                error_log("Error en listar cobros: " . $e->getMessage());
                echo json_encode([
                    "sEcho" => 1,
                    "iTotalRecords" => 0,
                    "iTotalDisplayRecords" => 0,
                    "aaData" => [],
                    "error" => $e->getMessage()
                ]);
            }
            break;
    
    }
?>