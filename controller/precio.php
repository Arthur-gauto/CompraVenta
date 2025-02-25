<?php
require_once("../config/conexion.php");
require_once("../models/Precio.php");

$precio = new Precio();

switch($_GET["op"]) {
    case "guardar":
        $prod_id = $_POST["prod_id"];
        $listp_a = $_POST["listp_a"];
        $listp_b = $_POST["listp_b"];
        $listp_c = $_POST["listp_c"];

        $precio->guardar_precios($prod_id, $listp_a, $listp_b, $listp_c);
        echo "Precios guardados correctamente";
        break;

    case "mostrar":
        $datos = $precio->get_precios($_POST["prod_id"]);
        if (!empty($datos)) {
            echo json_encode($datos[0]);
        } else {
            echo json_encode([]);
        }
        break;
}
?>