<?php
    //todo Llamando Clases
    require_once("../config/conexion.php");
    require_once("../models/Rol.php");
    //todo Iniciando Clase
    $rol=new Rol();

    switch($_GET["op"]){
        //todo Guardar y editar, guardar cuando el ID este vacio, y Actualizar cuando se envie el Id
        case "guardaryeditar":
            if(empty($_POST["rol_id"])){
                $rol->insert_rol($POST["suc_id"],$POST["rol_nom"]);
            }else{
                $rol->update_rol($POST["rol_id"],$POST["suc_id"],$POST["rol_nom"]);
            }
            break;

        //todo Listado de registros formato JSON para datable JS
        case "listar":
            $datos=$rol->get_rol_x_suc_id($POST["suc_id"]);
            $data=Array();
            foreach($datos as $row){
                $sub_array = array();
                $sub_array = $row["rol_nom"];
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
        case "mostar":
            $datos=$rol->get_rol_x_rol_id($POST["rol_id"]);
            if(is_array($datos)==true and count($datos)>0){
                foreach($datos as $row){
                    $output["rol_id"] = $row["rol_id"];
                    $output["suc_id"] = $row["suc_id"];
                    $output["rol_nom"] = $row["rol_nom"];
                }
                echo json_encode($output);
            }
            break;
        //todo Cambiar estado a 0 del Registro
        case "eliminar":
            $rol->delete_rol($POST["rol_id"]);
            break;

        

    }
?>