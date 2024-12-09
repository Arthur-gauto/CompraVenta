<?php
    //todo Llamando Clases
    require_once("../config/conexion.php");
    require_once("../models/Cliente.php");
    //todo Iniciando Clase
    $cliente=new Cliente();

    switch($_GET["op"]){
        //todo Guardar y editar, guardar cuando el ID este vacio, y Actualizar cuando se envie el Id
        case "guardaryeditar":
            if(empty($_POST["cli_id"])){
                $cliente->insert_cliente($POST["emp_id"],$POST["cli_nom"],$POST["cli_ruc"],$POST["cli_telf"],$POST["cli_direcc"],$POST["cli_correo"]);
            }else{
                $cliente->update_cliente($POST["cli_id"],$POST["emp_id"],$POST["cli_nom"],$POST["cli_ruc"],$POST["cli_telf"],$POST["cli_direcc"],$POST["cli_correo"]);
            }
            break;

        //todo Listado de registros formato JSON para datable JS
        case "listar":
            $datos=$cliente->get_cliente_x_emp_id($POST["emp_id"]);
            $data=Array();
            foreach($datos as $row){
                $sub_array = array();
                $sub_array = $row["cli_nom"];
                $sub_array = $row["cli_ruc"];
                $sub_array = $row["cli_telf"];
                $sub_array = $row["cli_direcc"];
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
            $datos=$cliente->get_cliente_x_cli_id($POST["cli_id"]);
            if(is_array($datos)==true and count($datos)>0){
                foreach($datos as $row){
                    $output["cli_id"] = $row["cli_id"];
                    $output["emp_id"] = $row["emp_id"];
                    $output["cli_nom"] = $row["cli_nom"];
                    $output["cli_ruc"] = $row["cli_ruc"];
                    $output["cli_telf"] = $row["cli_telf"];
                    $output["cli_direcc"] = $row["cli_direcc"];
                    $output["cli_correo"] = $row["cli_correo"];
                }
                echo json_encode($output);
            }
            break;
        //todo Cambiar estado a 0 del Registro
        case "eliminar":
            $cliente->delete_cliente($POST["cli_id"]);
            break;

        

    }
?>