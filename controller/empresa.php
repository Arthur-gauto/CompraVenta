<?php
    // Llamando Clases
    require_once("../config/conexion.php");
    require_once("../models/Empresa.php");
    // Iniciando Clase
    $empresa = new Empresa();

    switch($_GET["op"]){
        // Guardar y editar, guardar cuando el ID esté vacío, y actualizar cuando se envíe el ID
        case "guardaryeditar":
            if(empty($_POST["emp_id"])){
                $empresa->insert_empresa($_POST["com_id"], $_POST["emp_nom"], $_POST["emp_ruc"]);
            } else {
                $empresa->update_empresa($_POST["emp_id"], $_POST["com_id"], $_POST["emp_nom"], $_POST["emp_ruc"]);
            }
            break;

        // Listado de registros formato JSON para datable JS
        case "listar":
            $datos = $empresa->get_empresa_x_com_id($_POST["com_id"]);
            $data = array();
            foreach($datos as $row){
                $sub_array = array();
                $sub_array[] = $row["emp_nom"];
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
            $datos = $empresa->get_empresa_x_emp_id($_POST["emp_id"]);
            if(is_array($datos) && count($datos) > 0){
                foreach($datos as $row){
                    $output["emp_id"] = $row["emp_id"];
                    $output["com_id"] = $row["com_id"];
                    $output["emp_nom"] = $row["emp_nom"];
                    $output["emp_ruc"] = $row["emp_ruc"];
                }
                echo json_encode($output);
            }
            break;

        // Cambiar estado a 0 del Registro
        case "eliminar":
            $empresa->delete_empresa($_POST["emp_id"]);
            break;

        // Listar Combo
        case "combo":
            $datos = $empresa->get_empresa_x_com_id($_POST["com_id"]);
            if(is_array($datos) and count($datos) > 0){
                $html = "";
                $html .= "<option selected>Seleccionar</option>";
                foreach($datos as $row){
                    $html .= "<option value='".$row["EMP_ID"]."'>".$row["EMP_NOM"]."</option>";
                }
                echo $html;
            }
            break;
    }
?>
