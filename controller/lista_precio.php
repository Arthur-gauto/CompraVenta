<?php
    //todo Llamando Clases
    require_once("../config/conexion.php");
    require_once("../models/ListaPrecio.php");
    //todo Iniciando Clase
    $listaPrecio = new ListaPrecio();

    switch($_GET["op"]){
        //todo Guardar y editar, guardar cuando el ID esté vacío, y actualizar cuando se envíe el ID
        case "actualizar":
            $prod_id = $_POST["prod_id"];
            $listaPrecio->update_lista_precios($prod_id, 'A', $_POST["p_venta_a"]);
            $listaPrecio->update_lista_precios($prod_id, 'B', $_POST["p_venta_b"]);
            $listaPrecio->update_lista_precios($prod_id, 'C', $_POST["p_venta_c"]);
            break;

        //todo Listado de registros formato JSON para datable JS
        case "listar":
            $prod_id = $_POST["prod_id"];
            $datos = $listaPrecio->get_lista_precios($prod_id);
            $data = Array();
            foreach($datos as $row){
                $sub_array = array();
                $sub_array[] = $row["prod_id"];
                $sub_array[] = $row["tipo_lista"];
                $sub_array[] = $row["precio"];
                $data[] = $sub_array;
            }

            $results = array(
                "sEcho"=>1,
                "iTotalRecords"=>count($data),
                "iTotalDisplayRecords"=>count($data),
                "aaData"=>$data);
            echo json_encode($results);
            break;

        //todo Mostrar información de precios según el ID del producto
        case "mostrar":
            $prod_id = $_POST["prod_id"];
            $datos = $listaPrecio->get_lista_precios($prod_id);
            if(is_array($datos) == true and count($datos) > 0){
                foreach($datos as $row){
                    $output["prod_id"] = $row["prod_id"];
                    $output["tipo_lista"] = $row["tipo_lista"];
                    $output["precio"] = $row["precio"];
                }
                echo json_encode($output);
            }
            break;
    }
?>