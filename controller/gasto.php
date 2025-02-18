<?php
    //todo Llamando Clases
    require_once("../config/conexion.php");
    require_once("../models/Gasto.php");
    require_once("../models/Caja.php");
    //todo Iniciando Clase
    $gasto=new Gasto();
    $caja=new Caja();

    switch($_GET["op"]){
        //todo Guardar y editar, guardar cuando el ID este vacio, y Actualizar cuando se envie el Id
        case "guardaryeditar":
            error_log("gas_id: " . $_POST["gas_id"]);

            if(empty($_POST["gas_id"])){
                $caj_id = ($_POST["gas_tipo"] === "FIJO") ? NULL : $_POST["caj_id"];
                $gasto->insert_gasto($_POST["suc_id"],$caj_id,
                                    $_POST["gas_descrip"],$_POST["gas_tipo"],$_POST["gas_mon"]);
            }else{
                $gasto->update_gasto($_POST["gas_id"],$_POST["suc_id"],
                $_POST["gas_descrip"],$_POST["gas_tipo"], $_POST["gas_mon"]);
            }
            break;

        //todo Listado de registros formato JSON para datable JS
        case "listar":
            $datos=$gasto->get_gasto_x_suc_id($_POST["suc_id"]);
            $data=Array();
            foreach($datos as $row){
                $sub_array = array();
                $sub_array[] = $row["GAS_DESCRIP"];
                $sub_array[] = $row["GAS_TIPO"];
                $sub_array[] = $row["GAS_MON"];
                $sub_array[] = $row["FECH_CREA"];
                $sub_array[] = '<button type="button" onClick="editar('.$row["GAS_ID"].')" id="'.$row["GAS_ID"].'" class="btn btn-warning btn-icon waves-effect waves-light"><i class="ri-edit-2-line"></i></button>';
                $sub_array[] = '<button type="button" onClick="eliminar('.$row["GAS_ID"].')" id="'.$row["GAS_ID"].'" class="btn btn-danger btn-icon waves-effect waves-light"><i class="ri-delete-bin-5-line"></i></button>';
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
            $datos=$gasto->get_gasto_x_gas_id($_POST["gas_id"]);
            if(is_array($datos)==true and count($datos)>0){
                foreach($datos as $row){
                    $output["GAS_ID"] = $row["GAS_ID"];
                    $output["SUC_ID"] = $row["SUC_ID"];
                    $output["GAS_DESCRIP"] = $row["GAS_DESCRIP"];
                    $output["GAS_TIPO"] = $row["GAS_TIPO"];
                    $output["GAS_TIPO"] = $row["GAS_MON"];
                }
                echo json_encode($output);
            }
            break;
        //todo Cambiar estado a 0 del Registro
        case "eliminar":
            $gasto->delete_gasto($_POST["gas_id"]);
            break;
        
        case "combo":
            $datos = $gasto->get_gasto_x_suc_id($_POST["suc_id"]);
            if(is_array($datos)== true and count($datos) > 0){
                $html = "";
                $html .= "<option selected>Seleccionar</option>";
                foreach($datos as $row){
                    $html .= "<option value='".$row["GAS_ID"]."'>".$row["GAS_DESCRIP"]."</option>";
                }
                echo $html;
            }
        break;

        
        

    }
?>