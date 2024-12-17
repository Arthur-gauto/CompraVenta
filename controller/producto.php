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
                    $_POST["suc_id"],
                    $_POST["cat_id"],
                    $_POST["prod_nom"],
                    $_POST["prod_descrip"],      
                    $_POST["und_id"],
                    $_POST["mon_id"],
                    $_POST["prod_pcompra"],
                    $_POST["prod_pventa"],
                    $_POST["prod_stock"],
                    $_POST["prod_fechaven"],
                    $_POST["prod_img"]);
            }else{
                $producto->update_producto(
                $_POST["prod_id"],
                $_POST["suc_id"],
                $_POST["cat_id"],
                $_POST["prod_nom"],
                $_POST["prod_descrip"],
                $_POST["und_id"],
                $_POST["mon_id"],
                $_POST["prod_pcompra"],
                $_POST["prod_pventa"],
                $_POST["prod_stock"],
                $_POST["prod_fechaven"],
                $_POST["prod_img"]);
            }
            break;

        //todo Listado de registros formato JSON para datable JS
        case "listar":
            $datos=$producto->get_producto_x_suc_id($_POST["suc_id"]);
            $data=Array();
            foreach($datos as $row){
                $sub_array = array();
                $sub_array[] = $row["CAT_NOM"];
                $sub_array[] = $row["PROD_NOM"];
                $sub_array[] = $row["UND_NOM"];
                $sub_array[] = $row["MON_NOM"];
                $sub_array[] = $row["PROD_PCOMPRA"];
                $sub_array[] = $row["PROD_PVENTA"];
                $sub_array[] = $row["PROD_STOCK"];
                $sub_array[] = $row["FECH_CREA"];
                $sub_array[] = '<button type="button" onClick="editar('.$row["PROD_ID"].')" id="'.$row["PROD_ID"].'" class="btn btn-warning btn-icon waves-effect waves-light"><i class="ri-edit-2-line"></i></button>';
                $sub_array[] = '<button type="button" onClick="eliminar('.$row["PROD_ID"].')" id="'.$row["PROD_ID"].'" class="btn btn-danger btn-icon waves-effect waves-light"><i class="ri-delete-bin-5-line"></i></button>';
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
            $datos=$producto->get_producto_x_prod_id($_POST["prod_id"]);
            if(is_array($datos)==true and count($datos)>0){
                foreach($datos as $row){
                    $output["PROD_ID"] = $row["PROD_ID"];
                    $output["CAT_ID"] = $row["CAT_ID"];
                    $output["UND_ID"] = $row["UND_ID"];
                    $output["MON_ID"] = $row["MON_ID"];
                    $output["PROD_NOM"] = $row["PROD_NOM"];
                    $output["PROD_DESCRIP"] = $row["PROD_DESCRIP"];
                    $output["PROD_PCOMPRA"] = $row["PROD_PCOMPRA"];
                    $output["PROD_PVENTA"] = $row["PROD_PVENTA"];
                    $output["PROD_STOCK"] = $row["PROD_STOCK"];
                    $output["PROD_FECHAVEN"] = $row["PROD_FECHAVEN"];
                    $output["PROD_IMG"] = $row["PROD_IMG"];

                }
                echo json_encode($output);
            }
            break;
        //todo Cambiar estado a 0 del Registro
        case "eliminar":
            $producto->delete_producto($_POST["prod_id"]);
            break;

        

    }
?>