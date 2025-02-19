<?php
    //todo Llamando Clases
    require_once("../config/conexion.php");
    require_once("../models/Subcategoria.php");
    //todo Iniciando Clase
    $subcategoria=new Subcategoria();

    switch($_GET["op"]){
        //todo Guardar y editar, guardar cuando el ID este vacio, y Actualizar cuando se envie el Id
        case "guardaryeditar":
            error_log("scat_id: " . $_POST["scat_id"]);

            if(empty($_POST["scat_id"])){
                $subcategoria->insert_subcategoria($_POST["suc_id"],$_POST["cat_id"],$_POST["scat_nom"]);
            }else{
                $subcategoria->update_subcategoria($_POST["scat_id"],$_POST["suc_id"],$_POST["scat_nom"]);
            }
            break;

        //todo Listado de registros formato JSON para datable JS
        case "listar":
            $datos=$subcategoria->get_subcategoria_x_suc_id($_POST["suc_id"],$_POST["cat_id"]);
            $data=Array();
            foreach($datos as $row){
                $sub_array = array();
                $sub_array[] = $row["SCAT_NOM"];
                $sub_array[] = $row["FECH_CREA"];
                $sub_array[] = '<button type="button" onClick="editar('.$row["SCAT_ID"].')" id="'.$row["SCAT_ID"].'" class="btn btn-warning btn-icon waves-effect waves-light"><i class="ri-edit-2-line"></i></button>';
                $sub_array[] = '<button type="button" onClick="eliminar('.$row["SCAT_ID"].')" id="'.$row["SCAT_ID"].'" class="btn btn-danger btn-icon waves-effect waves-light"><i class="ri-delete-bin-5-line"></i></button>';
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
            $datos=$subcategoria->get_subcategoria_x_scat_id($_POST["scat_id"]);
            if(is_array($datos)==true and count($datos)>0){
                foreach($datos as $row){
                    $output["SCAT_ID"] = $row["SCAT_ID"];
                    $output["SUC_ID"] = $row["SUC_ID"];
                    $output["SCAT_NOM"] = $row["SCAT_NOM"];
                }
                echo json_encode($output);
            }
            break;
        //todo Cambiar estado a 0 del Registro
        case "eliminar":
            $subcategoria->delete_subcategoria($_POST["scat_id"]);
            break;
        
        case "combo":
            $datos = $subcategoria->get_subcategoria_x_suc_id($_POST["suc_id"],$_POST["cat_id"]);
            if(is_array($datos)== true and count($datos) > 0){
                $html = "";
                $html .= "<option selected>Seleccionar</option>";
                foreach($datos as $row){
                    $html .= "<option value='".$row["SCAT_ID"]."'>".$row["SCAT_NOM"]."</option>";
                }
                echo $html;
            }
        break;

        case "wombocombo":
            $datos = $subcategoria->get_subcategoria_total_stock($_POST["suc_id"]);
            foreach($datos as $row){
                ?>
                    <li class="py-1">
                        <a href="#" class="text-muted"><?php echo $row["SCAT_NOM"];?> <span class="float-end">(<?php echo $row["STOCK"];?>)</span></a>
                    </li>
                <?php
            }
        break;
        

    }
?>