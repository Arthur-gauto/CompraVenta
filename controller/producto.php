<?php
    //todo Llamando Clases
    require_once("../config/conexion.php");
    require_once("../models/Producto.php");
    //todo Iniciando Clase
    $producto=new Producto();

    switch($_GET["op"]){
        //todo Guardar y editar, guardar cuando el ID este vacio, y Actualizar cuando se envie el Id
        case "guardaryeditar":
            if (empty($_POST["prod_id"])) {
                // Insertar el producto y obtener el nuevo ID
                $nuevo_prod_id = $producto->insert_producto(
                    $_POST["suc_id"],
                    $_POST["cat_id"],
                    $_POST["scat_id"],
                    $_POST["prod_nom"],
                    $_POST["prod_descrip"],
                    $_POST["und_id"],
                    $_POST["mon_id"],
                    $_POST["prod_pcompra"],
                    $_POST["prod_pventa"],
                    $_POST["prod_stock"],
                    $_POST["prod_fechaven"],
                    $_POST["prod_img"]
                );
        
                if ($nuevo_prod_id > 0) {
                    // Insertar la lista de precios dentro del mismo flujo
                    $producto->insert_lista_precio($nuevo_prod_id, $_POST["prod_pcompra"]);
        
                    echo json_encode(["success" => true, "prod_id" => $nuevo_prod_id]); // Devolver ID
                } else {
                    echo json_encode(["success" => false, "error" => "No se pudo insertar el producto."]);
                }
            } else {
                // Actualizar producto si ya existe
                $producto->update_producto(
                    $_POST["prod_id"],
                    $_POST["suc_id"],
                    $_POST["cat_id"],
                    $_POST["scat_id"],
                    $_POST["prod_nom"],
                    $_POST["prod_descrip"],
                    $_POST["und_id"],
                    $_POST["mon_id"],
                    $_POST["prod_pcompra"],
                    null,
                    $_POST["prod_stock"],
                    null,
                    $_POST["prod_img"]
                );
        
                echo json_encode(["success" => true, "prod_id" => $_POST["prod_id"]]);
            }
            break;
        

        //todo Listado de registros formato JSON para datable JS
        case "listar":
            try {
                $datos=$producto->get_producto_x_suc_id($_POST["suc_id"]);
                $data= Array();
                foreach($datos as $row){
                    $sub_array = array();
                    if ($row["PROD_IMG"] != ''){
                        $sub_array[] = 
                        "<div class='d-flex align-items-center'>" .
                            "<div class='flex-shrink-0 me-2'>" .
                                "<img src='../../assets/producto/".$row["PROD_IMG"]."' alt='' class='avatar-xs rounded-circle'>".
                            "</div>".
                        "</div>";   
                    }else{
                        $sub_array[] = 
                        "<div class='d-flex align-items-center'>" .
                            "<div class='flex-shrink-0 me-2'>".
                                "<img src='../../assets/producto/no_imagen.png' alt='' class='avatar-xs rounded-circle'>".
                            "</div>".
                        "</div>";  
                    }
                    $sub_array[] = $row["CAT_NOM"];
                    $sub_array[] = $row["PROD_NOM"];
                    $sub_array[] = $row["UND_NOM"];
                    $sub_array[] = $row["MON_NOM"];
                    $sub_array[] = $row["PROD_PCOMPRA"];
                    $sub_array[] = $row["SCAT_NOM"];
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
                    "aaData"=>$data
                );
                
                header('Content-Type: application/json');
                echo json_encode($results, JSON_PRETTY_PRINT);
                
            } catch (Exception $e) {
                header('Content-Type: application/json');
                echo json_encode([
                    "sEcho" => 1,
                    "iTotalRecords" => 0,
                    "iTotalDisplayRecords" => 0,
                    "aaData" => [],
                    "error" => $e->getMessage()
                ]);
            }
        break;
        //todo Mostrar información de registro según su ID
        case "mostrar":
            $datos = $producto->get_producto_x_prod_id($_POST["prod_id"]);
            $output = [];
            if (is_array($datos) == true and count($datos) > 0) {
                foreach ($datos as $row) {
                    $output["PROD_ID"] = $row["PROD_ID"];
                    $output["CAT_ID"]  = $row["CAT_ID"];
                    $output["CAT_NOM"]  = $row["CAT_NOM"];
                    $output["SCAT_ID"]  = $row["SCAT_ID"];
                    $output["UND_ID"]  = $row["UND_ID"];
                    $output["UND_NOM"] = $row["UND_NOM"];
                    $output["MON_ID"]  = $row["MON_ID"];
                    $output["PROD_NOM"] = $row["PROD_NOM"];
                    $output["PROD_DESCRIP"] = $row["PROD_DESCRIP"];
                    $output["PROD_PCOMPRA"] = $row["PROD_PCOMPRA"];
                    $output["PROD_STOCK"] = $row["PROD_STOCK"];
                    $output["PROD_FECHAVEN"] = $row["PROD_FECHAVEN"];
                    $output["PROD_IMG"] = $row["PROD_IMG"];
                    $output["LISTP_A"] = $row["LISTP_A"] ?? ''; // Precios desde TM_LISTA_PRECIO
                    $output["LISTP_B"] = $row["LISTP_B"] ?? '';
                    $output["LISTP_C"] = $row["LISTP_C"] ?? '';
                    if ($row["PROD_IMG"] != '') {
                        $output["PROD_IMG"] = '<img src="../../assets/producto/'.$row["PROD_IMG"].'" class="rounded-circle avatar-xl img-thumbnail user-profile-image" alt="user-profile-image"></img><input type="hidden" name="hidden_producto_imagen" value="'.$row["PROD_IMG"].'" />';
                    } else {
                        $output["PROD_IMG"] = '<img src="../../assets/producto/no_imagen.png" class="rounded-circle avatar-xl img-thumbnail user-profile-image" alt="user-profile-image"></img><input type="hidden" name="hidden_producto_imagen" value="" />';
                    }
                }
                echo json_encode($output);
            }
            break;
        
        //todo Cambiar estado a 0 del Registro
        case "eliminar":
            $producto->delete_producto($_POST["prod_id"]);
            break;

        case "combo":
            $datos = $producto->get_producto_x_cat_id($_POST["cat_id"]);
            if(is_array($datos)== true and count($datos) > 0){
                $html = "";
                $html .= "<option selected>Seleccionar</option>";
                foreach($datos as $row){
                    $html .= "<option value='".$row["PROD_ID"]."'>".$row["PROD_NOM"]."</option>";
                }
                echo $html;
            }
        break;

        case "buscar_producto":
            $prod_nom = isset($_POST["prod_nom"]) ? $_POST["prod_nom"] : "";
            $datos = $producto->buscar_producto_nombre($prod_nom);
            if(is_array($datos) && count($datos) > 0){
                $html = "<option selected>Seleccionar</option>";
                foreach($datos as $row){
                    $html .= "<option value='".$row["PROD_ID"]."'>".$row["PROD_NOM"]."</option>";
                }
                echo $html;
            } else {
                echo "<option>No se encontraron productos</option>";
            }
        break;

        case "buscar":
            try {
                $term = $_POST["term"];
                $suc_id = $_POST["suc_id"];
                $datos = $producto->buscar_producto_venta($term, $suc_id);
                echo json_encode($datos);
            } catch (Exception $e) {
                echo json_encode([]);
            }
        break;
            
        case "precio":
            try {
                // Obtener los productos según la sucursal
                $datos = $producto->get_producto_x_suc_id($_POST["suc_id"]);
                $data = array();
                foreach ($datos as $row) {
                    $sub_array = array();
                    if ($row["PROD_IMG"] != ''){
                        $sub_array[] = 
                        "<div class='d-flex align-items-center'>" .
                            "<div class='flex-shrink-0 me-2'>" .
                                "<img src='../../assets/producto/".$row["PROD_IMG"]."' alt='' class='avatar-xs rounded-circle'>".
                            "</div>".
                        "</div>";   
                    } else {
                        $sub_array[] = 
                        "<div class='d-flex align-items-center'>" .
                            "<div class='flex-shrink-0 me-2'>".
                                "<img src='../../assets/producto/no_imagen.png' alt='' class='avatar-xs rounded-circle'>".
                            "</div>".
                        "</div>";  
                    }
                    $sub_array[] = $row["CAT_NOM"];
                    $sub_array[] = $row["SCAT_NOM"];
                    $sub_array[] = $row["PROD_NOM"];
                    $sub_array[] = $row["PROD_PCOMPRA"];
                    $sub_array[] = $row["LISTP_A"];
                    $sub_array[] = $row["LISTP_B"];
                    $sub_array[] = $row["LISTP_C"];
        
                    $data[] = $sub_array;
                }
        
                // Preparar la respuesta para DataTables
                $results = array(
                    "sEcho" => 1,
                    "iTotalRecords" => count($data),
                    "iTotalDisplayRecords" => count($data),
                    "aaData" => $data
                );
                
                header('Content-Type: application/json');
                echo json_encode($results, JSON_PRETTY_PRINT);
                
            } catch (Exception $e) {
                header('Content-Type: application/json');
                echo json_encode([
                    "sEcho" => 1,
                    "iTotalRecords" => 0,
                    "iTotalDisplayRecords" => 0,
                    "aaData" => [],
                    "error" => $e->getMessage()
                ]);
            }
            break;
        case "mostrar_listaprecio":
            $datos = $producto->get_lista_precio($_POST["prod_id"]);
            echo json_encode($datos);
            break;


            

    }
?>