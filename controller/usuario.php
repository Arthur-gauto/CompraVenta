<?php
    //todo Llamando Clases
    require_once("../config/conexion.php");
    require_once("../models/Usuario.php");
    //todo Iniciando Clase
    $usuario=new Usuario();

    switch($_GET["op"]){
        //todo Guardar y editar, guardar cuando el ID este vacio, y Actualizar cuando se envie el Id
        case "guardaryeditar":
            if(empty($_POST["usu_id"])){
                $usuario->insert_usuario(
                    $POST["suc_id"],
                    $POST["usu_correo"],
                    $POST["usu_nom"],
                    $POST["usu_ape"],
                    $POST["usu_dni"],
                    $POST["usu_telf"],
                    $POST["usu_pass"],
                    $POST["rol_id"]);
            }else{
                $usuario->update_usuario(
                    $POST["usu_id"],
                    $POST["suc_id"],
                    $POST["usu_correo"],
                    $POST["usu_nom"],
                    $POST["usu_ape"],
                    $POST["usu_dni"],
                    $POST["usu_telf"],
                    $POST["usu_pass"],
                    $POST["rol_id"]);
            }
            break;

        //todo Listado de registros formato JSON para datable JS
        case "listar":
            $datos=$usuario->get_usuario_x_suc_id($POST["suc_id"]);
            $data=Array();
            foreach($datos as $row){
                $sub_array = array();
                $sub_array = $row["usu_correo"];
                $sub_array = $row["usu_nom"];
                $sub_array = $row["usu_ape"];
                $sub_array = $row["usu_dni"];
                $sub_array = $row["usu_telf"];
                $sub_array = $row["usu_pass"];
                $sub_array = $row["rol_id"];
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
            $datos=$usuario->get_usuario_x_usu_id($POST["usu_id"]);
            if(is_array($datos)==true and count($datos)>0){
                foreach($datos as $row){
                    $output["usu_id"] = $row["usu_id"];
                    $output["suc_id"] = $row["suc_id"];
                    $output["usu_nom"] = $row["usu_nom"];
                    $output["usu_ape"] = $row["usu_ape"];
                    $output["usu_correo"] = $row["usu_correo"];
                    $output["usu_dni"] = $row["usu_dni"];
                    $output["usu_telf"] = $row["usu_telf"];
                    $output["usu_pass"] = $row["usu_pass"];
                    $output["rol_id"] = $row["rol_id"];
                }
                echo json_encode($output);
            }
            break;
        //todo Cambiar estado a 0 del Registro
        case "eliminar":
            $usuario->delete_usuario($POST["usu_id"]);
            break;

        

    }
?>