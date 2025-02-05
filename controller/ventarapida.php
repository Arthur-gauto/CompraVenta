<?php
    require_once("../config/conexion.php");
    require_once("../models/Venta.php");
    require_once("../models/Producto.php");
    require_once("../models/Caja.php");

    class VentaRapidaController extends Conectar {
        private $venta;
        private $producto;
        private $caja;

        public function __construct() {
            $this->venta = new Venta();
            $this->producto = new Producto();
            $this->caja = new Caja();
        }

        public function registrar_venta() {
            try {
                // Verificar si hay caja abierta
                $caja_actual = $this->caja->get_caja_abierta($_POST["suc_id"]);
                if (!$caja_actual) {
                    throw new Exception("No hay una caja abierta. Debe abrir caja primero.");
                }

                // Iniciar transacción
                $conectar = parent::Conexion();
                $conectar->beginTransaction();

                // Registrar la venta
                $venta_data = array(
                    'suc_id' => $_POST["suc_id"],
                    'usu_id' => $_POST["usu_id"],
                    'productos' => json_decode($_POST["productos"], true),
                    'total' => $_POST["total"],
                    'efectivo' => $_POST["efectivo"]
                );

                $venta_id = $this->venta->insertar_venta_rapida($venta_data);

                // Registrar en caja
                $this->caja->registrar_ingreso(
                    $caja_actual['CAJA_ID'],
                    $venta_id,
                    $_POST["total"],
                    "Venta Rápida #" . $venta_id
                );

                $conectar->commit();

                echo json_encode([
                    "status" => "success",
                    "venta_id" => $venta_id,
                    "message" => "Venta registrada correctamente"
                ]);

            } catch (Exception $e) {
                if (isset($conectar)) {
                    $conectar->rollBack();
                }
                echo json_encode([
                    "status" => "error",
                    "message" => $e->getMessage()
                ]);
            }
        }
    }

    // Instanciar el controlador
    $controller = new VentaRapidaController();

    // Manejar las operaciones
    switch($_GET["op"]) {
        case "registrar_venta":
            $controller->registrar_venta();
            break;
    }
?>