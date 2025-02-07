<?php
//TODO Llamando clase
require_once("../config/conexion.php");
require_once("../models/Compra.php");
//TODO Inicializando clase
$compra=new Compra();
switch($_GET["op"]){
    //TODO Guardar y editar, guardar cuando el cat_id esté vacío, actualizar cuando se reciba el id
    case "registrar":
        $nro_fact = $_POST["nro_fact"];  // Nuevo campo
        $fech_fact = $_POST["fech_fact"]; // Nuevo campo
        $datos = $compra->insert_compra_x_suc_id($_POST["suc_id"], $_POST["usu_id"], $nro_fact, $fech_fact);
        foreach ($datos as $row) {
            $output["COMPR_ID"] = $row["COMPR_ID"];
        }
        echo json_encode($output);
    break;
    
    
    case "guardardetalle":
        
        $datos=$compra->insert_compra_detalle($_POST["compr_id"],$_POST["prod_id"],
                                        $_POST["prod_pcompra"],$_POST["detc_cant"]);
        
        break;

    case "eliminardetalle":
        $datos=$compra->delete_compra_detalle($_POST["detc_id"]);        
        break;
    

    case "calculo":
        $datos=$compra->get_compra_calculo($_POST["compr_id"]); 
        foreach($datos as $row){
            $output["COMPR_SUBTOTAL"]=$row["COMPR_SUBTOTAL"];
            $output["COMPR_IGV"]=$row["COMPR_IGV"];
            $output["COMPR_TOTAL"]=$row["COMPR_TOTAL"];
        }
        echo json_encode($output);
        
    break;

    case "listardetalle":
        $datos=$compra->get_compra_detalle($_POST["compr_id"]);
        $data = Array();
        foreach($datos as $row){
            $sub_array = array();
            if ($row["PROD_IMG"] != ''){
                $sub_array[] = 
                    "<img src='ruta_a_tu_imagen' alt='Imagen Producto'>";
            } else {
                $sub_array[] = 
                    "<img src='ruta_a_tu_imagen_default' alt='Imagen Producto Default'>";
            }
            $sub_array[] = $row["CAT_NOM"];
            $sub_array[] = $row["PROD_NOM"];
            $sub_array[] = $row["UND_NOM"];
            $sub_array[] = $row["PROD_PCOMPRA"];
            $sub_array[] = $row["DETC_CANT"];
            $sub_array[] = $row["DETC_TOTAL"];
            $sub_array[] = '';
            $data[]=$sub_array;
        }
        $results = array(
            "sEcho"=>1,
            "iTotalRecords" =>count($data),
            "iTotalDisplayRecords"=>count($data),
            "aaData"=>$data);
        echo json_encode($results);
        break;

    case "listardetalleformato";
        $datos=$compra->get_compra_detalle($_POST["compr_id"]);
        foreach($datos as $row){
            ?>
                <tr>
                    <td>
                        <?php 
                            if ($row["PROD_IMG"] != ''){
                                ?>
                                    <?php
                                        echo "<div class='d-flex align-items-center'>" .
                                                "<div class='flex-shrink-0 me-2'>".
                                                    "<img src='../../assets/producto/".$row["PROD_IMG"]."' alt='' class='avatar-xs rounded-circle'>".
                                                "</div>".
                                            "</div>";
                                    ?>
                                <?php
                            }else{
                                ?>
                                    <?php 
                                        echo "<div class='d-flex align-items-center'>" .
                                                "<div class='flex-shrink-0 me-2'>".
                                                    "<img src='../../assets/producto/no_imagen.png' alt='' class='avatar-xs rounded-circle'>".
                                                "</div>".
                                            "</div>";
                                    ?>
                                <?php
                            }
                        ?>
                    </td>
                    <th><?php echo $row["CAT_NOM"];?></th>
                    <td><?php echo $row["PROD_NOM"];?></td>
                    <td scope="row"><?php echo $row["UND_NOM"];?></td>
                    <td scope="row"><?php echo $row["PROD_PCOMPRA"];?></td>
                    <td scope="row"><?php echo $row["DETC_CANT"];?></td>
                    <td class="text-end"><?php echo $row["DETC_TOTAL"];?></td>
                </tr>
            <?php
        }

    break;

    case "guardar":
        try {
            // Imprimir los datos recibidos para verificar
            echo "<pre>";
            print_r($_POST);
            echo "</pre>";

            // Preparar los datos necesarios
            $data = array(
                'SUC_ID' => $_POST["suc_id"],
                'PAG_ID' => $_POST["pag_id"],
                'PROV_ID' => $_POST["prov_id"],
                'PROV_RUC' => $_POST["prov_ruc"],
                'PROV_DIRECC' => $_POST["prov_direcc"],
                'PROV_CORREO' => $_POST["prov_correo"],
                'NRO_FACT' => $_POST["nro_fact"],
                'FECH_FACT' => $_POST["fech_fact"],
                'COMPR_SUBTOTAL' => $_POST["compr_subtotal"],
                'COMPR_IGV' => $_POST["compr_igv"],
                'COMPR_TOTAL' => $_POST["compr_total"],
                'COMPR_COMENT' => $_POST["compr_coment"],
                'USU_ID' => $_POST["usu_id"],
                'MON_ID' => $_POST["mon_id"],
                'DOC_ID' => $_POST["doc_id"],
                'FECH_CREA' => date('Y-m-d H:i:s'),
                'EST' => 1,
                'CAJA_ID' => $_POST["caja_id"],
                'detalle' => $_POST["detalle"]
            );

            // Registrar la compra con detalle
            $compra_id = $compra->insert_compra_con_detalle($data);

            // Actualizar stock y precio de los productos
            require_once("../models/Producto.php");
            $producto = new Producto();
            foreach ($_POST["detalle"] as $row) {
                $resultado = $producto->update_stock_precio_compra(
                    $row["prod_id"],
                    $row["detc_cant"],
                    $row["prod_pcompra"]
                );
                if (!$resultado) {
                    throw new Exception("Error al actualizar producto ID: " . $row["prod_id"]);
                }
            }

            // Actualizar cabecera de la compra
            $compra->update_compra(
                $compra_id,
                $_POST["pag_id"],
                $_POST["prov_id"],
                $_POST["prov_ruc"],
                $_POST["prov_direcc"],
                $_POST["prov_correo"],
                $_POST["compr_coment"],
                $_POST["mon_id"],
                $_POST["doc_id"],
                $_POST["nro_fact"],
                $_POST["fech_fact"]
            );

            echo json_encode([
                "status" => "success",
                "message" => "Compra registrada correctamente"
            ]);
        } catch (Exception $e) {
            echo json_encode([
                "status" => "error",
                "message" => $e->getMessage()
            ]);
        }
        break;
    

    case "mostrar":
        $datos = $compra->get_compra($_POST["compr_id"]); 
        foreach ($datos as $row) {
            $output["COMPR_ID"] = $row["COMPR_ID"];
            $output["SUC_ID"] = $row["SUC_ID"];
            $output["PAG_ID"] = $row["PAG_ID"];
            $output["PAG_NOM"] = $row["PAG_NOM"];
            $output["PROV_ID"] = $row["PROV_ID"];
            $output["PROV_RUC"] = $row["PROV_RUC"];
            $output["PROV_DIRECC"] = $row["PROV_DIRECC"];
            $output["PROV_CORREO"] = $row["PROV_CORREO"];
            $output["COMPR_SUBTOTAL"] = $row["COMPR_SUBTOTAL"];
            $output["COMPR_IGV"] = $row["COMPR_IGV"];
            $output["COMPR_TOTAL"] = $row["COMPR_TOTAL"];
            $output["COMPR_COMENT"] = $row["COMPR_COMENT"];
            $output["USU_ID"] = $row["USU_ID"];
            $output["USU_NOM"] = $row["USU_NOM"];
            $output["USU_APE"] = $row["USU_APE"];
            $output["USU_TELF"] = $row["USU_TELF"];
            $output["USU_CORREO"] = $row["USU_CORREO"];
            $output["MON_ID"] = $row["MON_ID"];
            $output["MON_NOM"] = $row["MON_NOM"];
            $output["FECH_CREA"] = $row["FECH_CREA"];
            $output["SUC_NOM"] = $row["SUC_NOM"];
            $output["EMP_NOM"] = $row["EMP_NOM"];
            $output["EMP_RUC"] = $row["EMP_RUC"];
            $output["EMP_CORREO"] = $row["EMP_CORREO"];
            $output["EMP_TELF"] = $row["EMP_TELF"];
            $output["EMP_DIRECC"] = $row["EMP_DIRECC"];
            $output["EMP_PAG"] = $row["EMP_PAG"];
            $output["COM_NOM"] = $row["COM_NOM"];
            $output["ROL_NOM"] = $row["ROL_NOM"];
            $output["PROV_NOM"] = $row["PROV_NOM"];
            
            // Nuevos campos agregados
            $output["NRO_FACT"] = $row["NRO_FACT"];  // Nuevo campo
            $output["FECH_FACT"] = $row["FECH_FACT"];  // Nuevo campo
        }
        echo json_encode($output);
    break;
    

    case "listarcompra":
        $datos = $compra->get_compra_listado($_POST["suc_id"]);
        $data = Array();
        foreach($datos as $row){
            $sub_array = array();
            $sub_array[] = "C-" . $row["COMPR_ID"];
            $sub_array[] = $row["DOC_NOM"];
            $sub_array[] = $row["PROV_RUC"];
            $sub_array[] = $row["NRO_FACT"];  
            $sub_array[] = $row["PROV_NOM"];
            $sub_array[] = $row["PAG_NOM"];
            $sub_array[] = $row["MON_NOM"];
            $sub_array[] = $row["COMPR_SUBTOTAL"];
            $sub_array[] = $row["COMPR_TOTAL"];
            $sub_array[] = $row["USU_NOM"] . " " . $row["USU_APE"];
            
            if ($row["USU_IMG"] != '') {
                $sub_array[] =
                "<div class='d-flex align-items-center'>" .
                    "<div class='flex-shrink-0 me-2'>" .
                        "<img src='../../assets/usuario/" . $row["USU_IMG"] . "' alt='' class='avatar-xs rounded-circle'>" .
                    "</div>" .
                "</div>";
            } else {
                $sub_array[] =
                "<div class='d-flex align-items-center'>" .
                    "<div class='flex-shrink-0 me-2'>" .
                        "<img src='../../assets/usuario/no_imagen.png' alt='' class='avatar-xs rounded-circle'>" .
                    "</div>" .
                "</div>";
            }
            
            $sub_array[] = '<a href="../ViewCompra/?c=' . $row["COMPR_ID"] . '" target="_blank" class="btn btn-primary btn-icon waves-effect waves-light"><i class="ri-printer-line"></i></a>';
            $sub_array[] = '<button type="button" onClick="ver(' . $row["COMPR_ID"] . ')" id="' . $row["COMPR_ID"] . '" class="btn btn-success btn-icon waves-effect waves-light"><i class="ri-settings-2-line"></i></button>';
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
    
    case "listartopproducto";
        $datos=$compra->get_compra_top_productos($_POST["suc_id"]);
        foreach($datos as $row){
            ?>
                <tr>
                    <td>
                        <div class="d-flex align-items-center">
                            <div class="avatar-sm bg-light rounded p-1 me-2">
                            <?php 
                                if ($row["PROD_IMG"] != ''){
                                    ?>
                                        <?php
                                            echo "<img src='../../assets/producto/".$row["PROD_IMG"]."' alt='' class='img-fluid d-block' />";
                                        ?>
                                    <?php
                                }else{
                                    ?>
                                        <?php 
                                            echo "<img src='../../assets/producto/no_imagen.png' alt='' class='img-fluid d-block' />";
                                        ?>
                                    <?php
                                }
                            ?>
                            </div>
                            <div>
                                <h5 class="fs-14 my-1"><?php echo $row["PROD_NOM"];?></h5>
                                <span class="text-muted"><?php echo $row["CAT_NOM"];?></span>
                            </div>
                        </div>
                    </td>
                    <td>
                        <h5 class="fs-14 my-1 fw-normal"><?php echo $row["PROD_PCOMPRA"];?></h5>
                        <span class="text-muted">P.Compra</span>
                    </td>
                    <td>
                        <h5 class="fs-14 my-1 fw-normal"><?php echo $row["CANT"];?></h5>
                        <span class="text-muted">Cant</span>
                    </td>
                    <td>
                        <h5 class="fs-14 my-1 fw-normal"><?php echo $row["PROD_STOCK"];?></h5>
                        <span class="text-muted">Stock</span>
                    </td>
                    <td>
                        <h5 class="fs-14 my-1 fw-normal"><b><?php echo $row["MON_NOM"];?></b> <?php echo $row["TOTAL"];?></h5>
                        <span class="text-muted">Total</span>
                    </td>
                </tr>
            <?php
        }

    break;

    case "top5":
        $datos = $compra->get_compra_top_5($_POST["suc_id"]);
        foreach ($datos as $row) {
            ?>
            <tr>
                <td>
                    C-<?php echo isset($row["COMPR_ID"]) ? $row["COMPR_ID"] : 'N/A'; ?>
                </td>
                <td>
                    <div class="d-flex align-items-center">
                        <div class="flex-shrink-0 me-2">
                            <?php
                            if (isset($row["USU_IMG"]) && $row["USU_IMG"] != '') {
                                echo "<img src='../../assets/usuario/" . $row["USU_IMG"] . "' alt='' class='avatar-xs rounded-circle' />";
                            } else {
                                echo "<img src='../../assets/usuario/no_imagen.png' alt='' class='avatar-xs rounded-circle' />";
                            }
                            ?>
                        </div>
                        <div class="flex-grow-1">
                            <?php echo isset($row["USU_NOM"]) ? $row["USU_NOM"] : 'N/A'; ?>
                            <?php echo isset($row["USU_APE"]) ? $row["USU_APE"] : ''; ?>
                        </div>
                    </div>
                </td>
                <td><?php echo isset($row["PROV_NOM"]) ? $row["PROV_NOM"] : 'N/A'; ?></td>
                <td><?php echo isset($row["MON_NOM"]) ? $row["MON_NOM"] : 'N/A'; ?></td>
                <td><?php echo isset($row["COMPR_SUBTOTAL"]) ? $row["COMPR_SUBTOTAL"] : 'N/A'; ?></td>
                <td><?php echo isset($row["COMPR_IGV"]) ? $row["COMPR_IGV"] : 'N/A'; ?></td>
                <td><?php echo isset($row["COMPR_TOTAL"]) ? $row["COMPR_TOTAL"] : 'N/A'; ?></td>
            </tr>
            <?php
        }
        break;
    

    case "compraventa":
        $datos=$compra->get_compraventa($_POST["suc_id"]);
        foreach($datos as $row){
            ?>
                <div class="acitivity-item py-3 d-flex">
                    <div class="flex-shrink-0 avatar-xs acitivity-avatar">
                        <?php 
                            if ($row["REGISTRO"] == 'Compra'){
                                ?>
                                    <div class="avatar-title bg-soft-success text-success rounded-circle">
                                        <i class="ri-shopping-cart-2-line"></i>
                                    </div>
                                <?php
                            }else{
                                ?>
                                    <div class="avatar-title bg-soft-danger text-danger rounded-circle">
                                        <i class="ri-stack-fill"></i>
                                    </div>
                                <?php
                            }
                        ?>
                        

                    </div>
                    <div class="flex-grow-1 ms-3">
                        <h6 class="mb-1 lh-base"><?php echo $row["REGISTRO"];?> - <?php echo $row["DOC_NOM"];?></h6>
                        <p class="text-muted mb-1"><?php echo $row["PROV_NOM"];?> </p>
                        <small class="mb-0 text-muted"><?php echo $row["FECH_CREA"];?></small>
                    </div>
                </div>
            <?php
        }
    break;

    case "dountcompra":
        $datos=$compra->get_consumocompra_categoria($_POST["suc_id"]);
        $data = array();
        foreach($datos as $row){
            $data[]=$row;
        }
        echo json_encode($data);
    break;

    case "barras":
        $datos=$compra->get_compra_barras($_POST["suc_id"]);
        $data = array();
        foreach($datos as $row){
            $data[]=$row;
        }
        echo json_encode($data);
    break;

    
}
?>