<?php
    //todo Llamando Clases
    require_once("../config/conexion.php");
    require_once("../models/Unidad.php");
    //todo Iniciando Clase
    $unidad=new Unidad();

    switch($_GET["op"]){
        //todo Guardar y editar, guardar cuando el ID este vacio, y Actualizar cuando se envie el Id
        case "guardaryeditar":
            if(empty($_POST["und_id"])){
                $unidad->insert_unidad($POST["suc_id"],$POST["und_nom"]);
            }else{
                $unidad->update_unidad($POST["und_id"],$POST["suc_id"],$POST["und_nom"]);
            }
            break;

        //todo Listado de registros formato JSON para datable JS
        case "listar":
            $datos=$unidad->get_unidad_x_suc_id($POST["suc_id"]);
            $data=Array();
            foreach($datos as $row){
                $sub_array = array();
                $sub_array = $row["und_nom"];
                $sub_array = "Editar";
                $sub_array = "Eliminar";
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
            $datos=$unidad->get_unidad_x_und_id($POST["und_id"]);
            if(is_array($datos)==true and count($datos)>0){
                foreach($datos as $row){
                    $output["und_id"] = $row["und_id"];
                    $output["suc_id"] = $row["suc_id"];
                    $output["und_nom"] = $row["und_nom"];
                }
                echo json_encode($output);
            }
            break;
        //todo Cambiar estado a 0 del Registro
        case "eliminar":
            $unidad->delete_unidad($POST["und_id"]);
            break;

        

    }
?>