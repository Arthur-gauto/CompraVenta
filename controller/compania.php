<?php
    //todo Llamando Clases
    require_once("../config/conexion.php");
    require_once("../models/Compania.php");
    //todo Iniciando Clase
    $compania=new Compania();

    switch($_GET["op"]){
        //todo Guardar y editar, guardar cuando el ID este vacio, y Actualizar cuando se envie el Id
        case "guardaryeditar":
            if(empty($_POST["com_id"])){
                $compania->insert_compania($_POST["com_nom"]);
            }else{
                $compania->update_compania($_POST["com_id"],$_POST["com_nom"]);
            }
            break;

        //todo Listado de registros formato JSON para datable JS
        case "listar":
            $datos=$compania->get_compania();
            $data=Array();
            foreach($datos as $row){
                $sub_array = array();
                $sub_array = $row["com_nom"];
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
            $datos=$compania->get_compania_x_com_id($_POST["com_id"]);
            if(is_array($datos)==true and count($datos)>0){
                foreach($datos as $row){
                    $output["com_id"] = $row["com_id"];
                    $output["com_nom"] = $row["com_nom"];
                }
                echo json_encode($output);
            }
            break;
        //todo Cambiar estado a 0 del Registro
        case "eliminar":
            $compania->delete_compania($_POST["com_id"]);
            break;

        

    }
?>