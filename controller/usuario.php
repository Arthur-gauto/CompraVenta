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
                    $_POST["suc_id"],
                    $_POST["usu_correo"],
                    $_POST["usu_nom"],
                    $_POST["usu_ape"],
                    $_POST["usu_dni"],
                    $_POST["usu_telf"],
                    $_POST["usu_pass"],
                    $_POST["rol_id"]);
            }else{
                $usuario->update_usuario(
                    $_POST["usu_id"],
                    $_POST["suc_id"],
                    $_POST["usu_correo"],
                    $_POST["usu_nom"],
                    $_POST["usu_ape"],
                    $_POST["usu_dni"],
                    $_POST["usu_telf"],
                    $_POST["usu_pass"],
                    $_POST["rol_id"]);
            }
            break;

        //todo Listado de registros formato JSON para datable JS
        case "listar":
            $datos=$usuario->get_usuario_x_suc_id($_POST["suc_id"]);
            $data=Array();
            foreach($datos as $row){
                $sub_array = array();
                $sub_array[] = $row["USU_CORREO"];
                $sub_array[] = $row["USU_NOM"];
                $sub_array[] = $row["USU_APE"];
                $sub_array[] = $row["USU_DNI"];
                $sub_array[] = $row["USU_TELF"];
                $sub_array[] = $row["USU_PASS"];
                $sub_array[] = $row["ROL_ID"];
                $sub_array[] = $row["FECH_CREA"];
                $sub_array[] = '<button type="button" onClick="editar('.$row["USU_ID"].')" id="'.$row["USU_ID"].'" class="btn btn-warning btn-icon waves-effect waves-light"><i class="ri-edit-2-line"></i></button>';
                $sub_array[] = '<button type="button" onClick="eliminar('.$row["USU_ID"].')" id="'.$row["USU_ID"].'" class="btn btn-danger btn-icon waves-effect waves-light"><i class="ri-delete-bin-5-line"></i></button>';
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
            $datos=$usuario->get_usuario_x_usu_id($_POST["usu_id"]);
            if(is_array($datos)==true and count($datos)>0){
                foreach($datos as $row){
                    $output["USU_ID"] = $row["USU_ID"];
                    $output["SUC_ID"] = $row["SUC_ID"];
                    $output["USU_NOM"] = $row["USU_NOM"];
                    $output["USU_APE"] = $row["USU_APE"];
                    $output["USU_CORREO"] = $row["USU_CORREO"];
                    $output["USU_DNI"] = $row["USU_DNI"];
                    $output["USU_TELF"] = $row["USU_TELF"];
                    $output["USU_PASS"] = $row["USU_PASS"];
                    $output["ROL_ID"] = $row["ROL_ID"];
                }
                echo json_encode($output);
            }
            break;
        //todo Cambiar estado a 0 del Registro
        case "eliminar":
            $usuario->delete_usuario($_POST["usu_id"]);
            break;

        case "actualizar":
            $usuario->update_usuario_pass($_POST["usu_id"],$_POST["usu_pass"]);
            break;

    }
?>