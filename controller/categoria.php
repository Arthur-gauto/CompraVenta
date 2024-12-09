<?php
    //todo Llamando Clases
    require_once("../config/conexion.php");
    require_once("../models/Categoria.php");
    //todo Iniciando Clase
    $categoria=new Categoria();

    switch($_GET["op"]){
        //todo Guardar y editar, guardar cuando el ID este vacio, y Actualizar cuando se envie el Id
        case "guardaryeditar":
            if(empty($_POST["cat_id"])){
                $categoria->insert_categoria($POST["suc_id"],$POST["cat_nom"]);
            }else{
                $categoria->update_categoria($POST["cat_id"],$POST["suc_id"],$POST["cat_nom"]);
            }
            break;

        //todo Listado de registros formato JSON para datable JS
        case "listar":
            $datos=$categoria->get_categoria_x_suc_id($POST["suc_id"]);
            $data=Array();
            foreach($datos as $row){
                $sub_array = array();
                $sub_array = $row["cat_nom"];
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
            $datos=$categoria->get_categoria_x_cat_id($POST["cat_id"]);
            if(is_array($datos)==true and count($datos)>0){
                foreach($datos as $row){
                    $output["cat_id"] = $row["cat_id"];
                    $output["suc_id"] = $row["suc_id"];
                    $output["cat_nom"] = $row["cat_nom"];
                }
                echo json_encode($output);
            }
            break;
        //todo Cambiar estado a 0 del Registro
        case "eliminar":
            $categoria->delete_categoria($POST["cat_id"]);
            break;

        

    }
?>