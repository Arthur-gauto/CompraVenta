<?php
    //todo Llamando Clases
    require_once("../config/conexion.php");
    require_once("../models/Producto.php");
    //todo Iniciando Clase
    $producto=new Producto();

    switch($_GET["op"]){
        //todo Guardar y editar, guardar cuando el ID este vacio, y Actualizar cuando se envie el Id
        case "guardaryeditar":
            if(empty($_POST["prod_id"])){
                $producto->insert_producto(
                    $POST["suc_id"],
                    $POST["cat_id"],
                    $POST["prod_nom"],
                    $POST["prod_descrip"],      
                    $POST["und_id"],
                    $POST["mon_id"],
                    $POST["prod_pcompra"],
                    $POST["prod_pventa"],
                    $POST["prod_stock"],
                    $POST["prod_fechaven"],
                    $POST["prod_img"]);
            }else{
                $producto->update_producto(
                $POST["prod_id"],
                $POST["suc_id"],
                $POST["cat_id"],
                $POST["prod_nom"],
                $POST["prod_descrip"],
                $POST["und_id"],
                $POST["mon_id"],
                $POST["prod_pcompra"],
                $POST["prod_pventa"],
                $POST["prod_stock"],
                $POST["prod_fechaven"],
                $POST["prod_img"]);
            }
            break;

        //todo Listado de registros formato JSON para datable JS
        case "listar":
            $datos=$producto->get_producto_x_suc_id($POST["suc_id"]);
            $data=Array();
            foreach($datos as $row){
                $sub_array = array();
                $sub_array = $row["prod_nom"];
                $sub_array = $row["prod_descrip"];
                $sub_array = $row["prod_pcompra"];
                $sub_array = $row["prod_pventa"];
                $sub_array = $row["prod_stock"];
                $sub_array = $row["prod_fechaven"];
                $sub_array = $row["prod_img"];
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
            $datos=$producto->get_producto_x_prod_id($POST["prod_id"]);
            if(is_array($datos)==true and count($datos)>0){
                foreach($datos as $row){
                    $output["prod_id"] = $row["prod_id"];
                    $output["cat_id"] = $row["cat_id"];
                    $output["prod_nom"] = $row["prod_nom"];
                    $output["prod_descrip"] = $row["prod_descrip"];
                    $output["prod_pcompra"] = $row["prod_pcompra"];
                    $output["prod_pventa"] = $row["prod_pventa"];
                    $output["prod_stock"] = $row["prod_stock"];
                    $output["prod_fechaven"] = $row["prod_fechaven"];
                    $output["prod_img"] = $row["prod_img"];

                }
                echo json_encode($output);
            }
            break;
        //todo Cambiar estado a 0 del Registro
        case "eliminar":
            $producto->delete_producto($POST["prod_id"]);
            break;

        

    }
?>