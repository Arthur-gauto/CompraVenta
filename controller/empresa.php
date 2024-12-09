<?php
    //todo Llamando Clases
    require_once("../config/conexion.php");
    require_once("../models/Empresa.php");
    //todo Iniciando Clase
    $empresa=new Empresa();

    switch($_GET["op"]){
        //todo Guardar y editar, guardar cuando el ID este vacio, y Actualizar cuando se envie el Id
        case "guardaryeditar":
            if(empty($_POST["emp_id"])){
                $empresa->insert_empresa($POST["com_id"],$POST["emp_nom"],$POST["emp_ruc"]);
            }else{
                $empresa->update_empresa($POST["emp_id"],$POST["com_id"],$POST["emp_nom"],$POST["emp_ruc"]);
            }
            break;

        //todo Listado de registros formato JSON para datable JS
        case "listar":
            $datos=$empresa->get_empresa_x_com_id($POST["com_id"]);
            $data=Array();
            foreach($datos as $row){
                $sub_array = array();
                $sub_array = $row["emp_nom"];
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
            $datos=$empresa->get_empresa_x_emp_id($POST["emp_id"]);
            if(is_array($datos)==true and count($datos)>0){
                foreach($datos as $row){
                    $output["emp_id"] = $row["emp_id"];
                    $output["com_id"] = $row["com_id"];
                    $output["emp_nom"] = $row["emp_nom"];
                    $output["emp_ruc"] = $row["emp_ruc"];
                }
                echo json_encode($output);
            }
            break;
        //todo Cambiar estado a 0 del Registro
        case "eliminar":
            $empresa->delete_empresa($POST["emp_id"]);
            break;

        

    }
?>