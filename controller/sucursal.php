<?php
    // Llamando Clases
    require_once("../config/conexion.php");
    require_once("../models/Sucursal.php");
    // Iniciando Clase
    $sucursal = new Sucursal();

    switch($_GET["op"]){
        // Guardar y editar, guardar cuando el ID esté vacío, y actualizar cuando se envíe el ID
        case "guardaryeditar":
            if(empty($_POST["emp_id"])){
                $sucursal->insert_sucursal($_POST["emp_id"], $_POST["suc_nom"]);
            } else {
                $sucursal->update_sucursal($_POST["suc_id"], $_POST["emp_id"], $_POST["suc_nom"]);
            }
            break;

        // Listado de registros formato JSON para datable JS
        case "listar":
            $datos = $sucursal->get_sucursal_x_emp_id($_POST["emp_id"]);
            $data = array();
            foreach($datos as $row){
                $sub_array = array();
                $sub_array[] = $row["SUC_NOM"];
                $sub_array[] = "Editar";
                $sub_array[] = "Eliminar";
                $data[] = $sub_array;
            }

            $results = array(
                "sEcho" => 1,
                "iTotalRecords" => count($data),
                "iTotalDisplayRecords" => count($data),
                "aaData" => $data
            );
            echo json_encode($results);
            break;

        // Mostrar información de registro según su ID
        case "mostrar":
            $datos = $sucursal->get_sucursal_x_emp_id($_POST["emp_id"]);
            if(is_array($datos) && count($datos) > 0){
                foreach($datos as $row){
                    $output["emp_id"] = $row["emp_id"];
                    $output["emp_id"] = $row["emp_id"];
                    $output["emp_nom"] = $row["emp_nom"];
                    $output["emp_ruc"] = $row["emp_ruc"];
                }
                echo json_encode($output);
            }
            break;

        // Cambiar estado a 0 del Registro
        case "eliminar":
            $sucursal->delete_sucursal($_POST["suc_id"]);
            break;

        // Listar Combo
        case "combo":
            $datos = $sucursal->get_sucursal_x_emp_id($_POST["emp_id"]);
            if(is_array($datos) and count($datos) > 0){
                $html = "";
                $html .= "<option selected>Seleccionar</option>";
                foreach($datos as $row){
                    $html .= "<option value='".$row["SUC_ID"]."'>".$row["SUC_NOM"]."</option>";
                }
                echo $html;
            }
            break;
    }
?>
