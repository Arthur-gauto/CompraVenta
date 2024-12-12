<?php
    //todo Llamando Clases
    require_once("../config/conexion.php");
    require_once("../models/Proveedor.php");
    //todo Iniciando Clase
    $proveedor=new Proveedor();

    switch($_GET["op"]){
        //todo Guardar y editar, guardar cuando el ID este vacio, y Actualizar cuando se envie el Id
        case "guardaryeditar":
            if(empty($_POST["prov_id"])){
                $proveedor->insert_proveedor($POST["emp_id"],$POST["prov_nom"],$POST["prov_ruc"],$POST["prov_telf"],$POST["prov_direcc"],$POST["prov_correo"]);
            }else{
                $proveedor->update_proveedor($POST["prov_id"],$POST["emp_id"],$POST["prov_nom"],$POST["prov_ruc"],$POST["prov_telf"],$POST["prov_direcc"],$POST["prov_correo"]);
            }
            break;

        //todo Listado de registros formato JSON para datable JS
        case "listar":
            $datos=$proveedor->get_proveedor_x_emp_id($POST["emp_id"]);
            $data=Array();
            foreach($datos as $row){
                $sub_array = array();
                $sub_array = $row["prov_nom"];
                $sub_array = $row["prov_ruc"];
                $sub_array = $row["prov_telf"];
                $sub_array = $row["prov_direcc"];
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
            $datos=$proveedor->get_proveedor_x_prov_id($POST["prov_id"]);
            if(is_array($datos)==true and count($datos)>0){
                foreach($datos as $row){
                    $output["prov_id"] = $row["prov_id"];
                    $output["emp_id"] = $row["emp_id"];
                    $output["prov_nom"] = $row["prov_nom"];
                    $output["prov_ruc"] = $row["prov_ruc"];
                    $output["prov_telf"] = $row["prov_telf"];
                    $output["prov_direcc"] = $row["prov_direcc"];
                    $output["prov_correo"] = $row["prov_correo"];
                }
                echo json_encode($output);
            }
            break;
        //todo Cambiar estado a 0 del Registro
        case "eliminar":
            $proveedor->delete_proveedor($POST["prov_id"]);
            break;

        

    }
?>