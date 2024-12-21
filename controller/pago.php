<?php
    //todo Llamando Clases
    require_once("../config/conexion.php");
    require_once("../models/Pago.php");
    //todo Iniciando Clase
    $pago=new Pago();

    switch($_GET["op"]){
        case "combo":
            $datos = $pago->get_pago();
            if(is_array($datos)== true and count($datos) > 0){
                $html = "";
                $html .= "<option selected>Seleccionar</option>";
                foreach($datos as $row){
                    $html .= "<option value='".$row["PAG_ID"]."'>".$row["PAG_NOM"]."</option>";
                }
                echo $html;
            }
        break;
    }
?>