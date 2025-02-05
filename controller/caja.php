<?php
    require_once("../config/conexion.php");
    require_once("../models/Caja.php");
    $caja = new Caja();

    switch($_GET["op"]){
        case "listar":
            try {
                $datos = $caja->get_caja($_POST["suc_id"]);
                $data = Array();
                foreach($datos as $row){
                    $sub_array = array();
                    $sub_array[] = $row["CAJA_ID"];
                    $sub_array[] = $row["SUC_NOM"];
                    $sub_array[] = $row["USU_NOM"];
                    $sub_array[] = $row["CAJA_FECHA"];
                    $sub_array[] = $row["CAJA_INGRESO"];
                    $sub_array[] = $row["CAJA_EGRESO"];
                    $sub_array[] = $row["CAJA_SALDO"];
                    $sub_array[] = $row["FECH_CREA"];
                    
                    if ($row["EST"]==1){
                        $sub_array[] = '<span class="badge bg-success">ABIERTA</span>';
                    }else{
                        $sub_array[] = '<span class="badge bg-danger">CERRADA</span>';
                    }

                    $sub_array[] = '<button type="button" onClick="ver('.$row["CAJA_ID"].')" id="'.$row["CAJA_ID"].'" class="btn btn-info btn-icon waves-effect waves-light"><i class="ri-eye-fill"></i></button>';
                    $sub_array[] = '<button type="button" onClick="eliminar('.$row["CAJA_ID"].')" id="'.$row["CAJA_ID"].'" class="btn btn-danger btn-icon waves-effect waves-light"><i class="ri-delete-bin-5-line"></i></button>';
                    $data[] = $sub_array;
                }

                $results = array(
                    "sEcho"=>1,
                    "iTotalRecords"=>count($data),
                    "iTotalDisplayRecords"=>count($data),
                    "aaData"=>$data);
                echo json_encode($results);
            } catch (Exception $e) {
                error_log("Error en listar: " . $e->getMessage());
                echo json_encode(["error" => $e->getMessage()]);
            }
            break;

            case "guardaryeditar":
                try {
                    // Log de datos recibidos
                    error_log("Datos recibidos: " . json_encode($_POST));
                    
                    if(empty($_POST["caja_id"])){
                        $datos = $caja->insert_caja(
                            $_POST["suc_id"],
                            $_POST["usu_id"],
                            $_POST["caja_fecha"]
                        );
                        // Log del resultado
                        error_log("Resultado insert: " . json_encode($datos));
                        echo json_encode($datos);
                    }
                } catch (Exception $e) {
                    // Log de error
                    error_log("Error en guardaryeditar: " . $e->getMessage());
                    echo json_encode(["error" => $e->getMessage()]);
                }
                break;

        case "mostrar":
            $datos=$caja->get_caja_x_id($_POST["caja_id"]);  
            if(is_array($datos)==true and count($datos)>0){
                foreach($datos as $row){
                    $output["CAJA_ID"] = $row["CAJA_ID"];
                    $output["SUC_ID"] = $row["SUC_ID"];
                    $output["USU_ID"] = $row["USU_ID"];
                    $output["CAJA_FECHA"] = $row["CAJA_FECHA"];
                    $output["CAJA_INGRESO"] = $row["CAJA_INGRESO"];
                    $output["CAJA_EGRESO"] = $row["CAJA_EGRESO"];
                    $output["CAJA_SALDO"] = $row["CAJA_SALDO"];
                }
                echo json_encode($output);
            }
            break;

        case "eliminar":
            $caja->delete_caja($_POST["caja_id"]);
            break;


            case "listar_detalle":
                try {
                    $datos = $caja->get_caja_detalle($_POST["caja_id"]);
                    $data = Array();
                    foreach($datos as $row){
                        $sub_array = array();
                        $sub_array[] = $row["ID"];
                        $sub_array[] = $row["TIPO"] == 'I' ? 
                            '<span class="badge bg-success">INGRESO</span>' : 
                            '<span class="badge bg-danger">EGRESO</span>';
                        $sub_array[] = $row["REFERENCIA"];
                        $sub_array[] = $row["FECHA"];
                        $sub_array[] = $row["PAG_NOM"];
                        $sub_array[] = $row["MON_NOM"];
                        $sub_array[] = $row["MONTO"];
                        $sub_array[] = $row["COMENTARIO"];
                        $sub_array[] = $row["EST"] == 1 ? 
                            '<span class="badge bg-success">ACTIVO</span>' : 
                            '<span class="badge bg-danger">ANULADO</span>';
                        $data[] = $sub_array;
                    }
            
                    $results = array(
                        "sEcho"=>1,
                        "iTotalRecords"=>count($data),
                        "iTotalDisplayRecords"=>count($data),
                        "aaData"=>$data);
                    echo json_encode($results);
                } catch (Exception $e) {
                    error_log("Error en listar_detalle: " . $e->getMessage());
                    echo json_encode(["error" => $e->getMessage()]);
                }
            break;
    
            case "mostrar_detalle":
                $datos=$caja->get_caja_x_id($_POST["caja_id"]);  
                if(is_array($datos)==true and count($datos)>0){
                    foreach($datos as $row){
                        $output["CAJA_ID"] = $row["CAJA_ID"];
                        $output["SUC_ID"] = $row["SUC_ID"];
                        $output["SUC_NOM"] = $row["SUC_NOM"];
                        $output["USU_ID"] = $row["USU_ID"];
                        $output["USU_NOM"] = $row["USU_NOM"];
                        $output["CAJA_FECHA"] = $row["CAJA_FECHA"];
                        $output["CAJA_INGRESO"] = $row["CAJA_INGRESO"];
                        $output["CAJA_EGRESO"] = $row["CAJA_EGRESO"];
                        $output["CAJA_SALDO"] = $row["CAJA_SALDO"];
                    }
                    echo json_encode($output);
                }
                break;
    }

?>