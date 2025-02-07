<?php
    //todo Llamando Clases
    require_once("../config/conexion.php");
    require_once("../models/Caja.php");
    //todo Iniciando Clase
    $caja=new Caja();

    switch($_GET["op"]){
        //todo Guardar y editar, guardar cuando el ID este vacio, y Actualizar cuando se envie el Id
        case "guardaryeditar":
            error_log("caj_id: " . $_POST["caj_id"]);
        
            try {
                if (empty($_POST["caj_id"])) {
                    // Verificar si hay una caja abierta
                    $caja_abierta = $caja->verificar_caja_abierta($_POST["suc_id"]);
                    
                    if ($caja_abierta) {
                        echo json_encode(["status" => "error", "message" => "Ya existe una caja abierta."]);
                        exit();
                    }
        
                    // Si no hay caja abierta, crear una nueva
                    $caja->insert_caja($_POST["suc_id"], $_POST["usu_id"], $_POST["caj_ini"]);
                    echo json_encode(["status" => "success", "message" => "Caja abierta correctamente."]);
                } else {
                    // Actualizar caja existente
                    $caja->update_caja($_POST["caj_id"], $_POST["caj_ing"], $_POST["caj_egr"], $_POST["caj_fin"]);
                    echo json_encode(["status" => "success", "message" => "Caja actualizada correctamente."]);
                }
            } catch (PDOException $e) {
                echo json_encode(["status" => "error", "message" => $e->getMessage()]);
            }
        break;
        

        //todo Listado de registros formato JSON para datable JS
        case "listar":
            $datos=$caja->get_caja_x_suc_id($_POST["suc_id"]);
            $data=Array();
            foreach($datos as $row){
                $sub_array = array();
                $sub_array[] = $row["FECH_CREA"];
                if ($row["USU_IMG"] != '') {
                    $sub_array[] =
                    "<div class='d-flex align-items-center'>" .
                        "<div class='flex-shrink-0 me-2'>" .
                            "<img src='../../assets/usuario/" . $row["USU_IMG"] . "' alt='' class='avatar-xs rounded-circle'>" .
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
                $sub_array[] = $row["USU_NOM"];
                $sub_array[] = $row["CAJ_INI"];
                $sub_array[] = $row["CAJ_FIN"];
                if ($row["EST"]==1){
                    $sub_array[]='<span class="badge bg-success">ABIERTA</span>';
                }else{
                    $sub_array[]='<span class="badge bg-danger">CERRADA</span>';
                }
                $sub_array[] = '<button type="button"  onClick="ver('.$row["CAJ_ID"].')" id="'.$row["CAJ_ID"].'" class="btn btn-info btn-icon waves-effect waves-light"><i class="ri-eye-fill"></i></button>';
                $sub_array[] = '<button type="button" onClick="eliminar('.$row["CAJ_ID"].')" id="'.$row["CAJ_ID"].'" class="btn btn-danger btn-icon waves-effect waves-light"><i class="ri-delete-bin-5-line"></i></button>';
                $data[] = $sub_array;
            }

            $results = array(
                "sEcho"=>1,
                "iTotalRecords"=>count($data),
                "iTotalDisplayRecords"=>count($data),
                "aaData"=>$data);
            echo json_encode($results);
            break;
        //todo Mostrar información de registro según su ID
        case "mostrar":
            $datos=$caja->get_caja_x_suc_id($_POST["suc_id"]);
            if(is_array($datos)==true and count($datos)>0){
                foreach($datos as $row){
                    $output["CAJ_ID"] = $row["CAJ_ID"];
                    $output["SUC_ID"] = $row["SUC_ID"];
                    $output["USU_ID"] = $row["USU_ID"];
                    $output["SUC_NOM"] = $row["SUC_NOM"];
                    $output["USU_NOM"] = $row["USU_NOM"];
                    $output["CAJ_ING"] = $row["CAJ_ING"];
                    $output["CAJ_EGR"] = $row["CAJ_EGR"];
                    $output["CAJ_FIN"] = $row["CAJ_FIN"];
                    $output["FECH_CREA"] = $row["FECH_CREA"];
                }
                echo json_encode($output);
            }
            break;
        case "mostrarcaj":
            $datos=$caja->get_caja_x_caj_id($_POST["caj_id"]);
            if(is_array($datos)==true and count($datos)>0){
                foreach($datos as $row){
                    $output["CAJ_ID"] = $row["CAJ_ID"];
                    $output["SUC_ID"] = $row["SUC_ID"];
                    $output["USU_ID"] = $row["USU_ID"];
                    $output["SUC_NOM"] = $row["SUC_NOM"];
                    $output["USU_NOM"] = $row["USU_NOM"];
                    $output["CAJ_ING"] = $row["CAJ_ING"];
                    $output["CAJ_EGR"] = $row["CAJ_EGR"];
                    $output["CAJ_FIN"] = $row["CAJ_FIN"];
                    $output["FECH_CREA"] = $row["FECH_CREA"];
                }
                echo json_encode($output);
            }
            break;
        //todo Cambiar estado a 0 del Registro
        case "eliminar":
            $caja->delete_caja($_POST["caj_id"]);
            break;
        
        case "combo":
            $datos = $caja->get_caja_x_suc_id($_POST["suc_id"]);
            if(is_array($datos)== true and count($datos) > 0){
                $html = "";
                $html .= "<option selected>Seleccionar</option>";
                foreach($datos as $row){
                    $html .= "<option value='".$row["CAJ_ID"]."'>".$row["CAT_NOM"]."</option>";
                }
                echo $html;
            }
        break;

        
        

    }
?>