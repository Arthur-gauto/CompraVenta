<?php
    //todo Llamando Clases
    require_once("../config/conexion.php");
    require_once("../models/Moneda.php");
    //todo Iniciando Clase
    $moneda=new Moneda();

    switch($_GET["op"]){
        //todo Guardar y editar, guardar cuando el ID este vacio, y Actualizar cuando se envie el Id
        case "guardaryeditar":
            if(empty($_POST["mon_id"])){
                $moneda->insert_moneda($POST["suc_id"],$POST["mon_nom"]);
            }else{
                $moneda->update_moneda($POST["mon_id"],$POST["suc_id"],$POST["mon_nom"]);
            }
            break;

        //todo Listado de registros formato JSON para datable JS
        case "listar":
            $datos=$moneda->get_moneda_x_suc_id($POST["suc_id"]);
            $data=Array();
            foreach($datos as $row){
                $sub_array = array();
                $sub_array = $row["mon_nom"];
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
            $datos=$moneda->get_moneda_x_mon_id($POST["mon_id"]);
            if(is_array($datos)==true and count($datos)>0){
                foreach($datos as $row){
                    $output["mon_id"] = $row["mon_id"];
                    $output["suc_id"] = $row["suc_id"];
                    $output["mon_nom"] = $row["mon_nom"];
                }
                echo json_encode($output);
            }
            break;
        //todo Cambiar estado a 0 del Registro
        case "eliminar":
            $moneda->delete_moneda($POST["mon_id"]);
            break;

        

    }
?>