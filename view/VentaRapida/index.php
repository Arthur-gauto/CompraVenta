<?php
    require_once("../../config/conexion.php");
    require_once("../../models/Rol.php");
    $rol = new Rol();
    $datos = $rol->validar_menu_x_rol($_SESSION["ROL_ID"], "ventarapida");
    if (isset($_SESSION["USU_ID"]) AND count($datos) > 0) {
?>

<!doctype html>
<html lang="es" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none">
<head>
    <title>Venta Rápida | <?php echo $_SESSION["EMP_NOM"]; ?></title>
    <?php require_once("../html/head.php"); ?>
</head>

<body>
    <div id="layout-wrapper">
        <?php require_once("../html/header.php"); ?>
        <?php require_once("../html/menu.php"); ?>

        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    <div class="row">
                        <div class="col-12">
                            <div class="page-title-box d-sm-flex align-items-center justify-content-between">
                                <h4 class="mb-sm-0">Venta Rápida</h4>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <!-- Panel Izquierdo - Búsqueda y Lista de Productos -->
                        <div class="col-md-8">
                            <div class="card">
                                <div class="card-body">
                                    <!-- Búsqueda Rápida de Productos -->
                                    <div class="row mb-3">
                                        <div class="col-md-12">
                                            <div class="input-group input-group-lg">
                                                <input type="text" id="buscar_producto" class="form-control" 
                                                       placeholder="Buscar producto por código o nombre...">
                                                <span class="input-group-text"><i class="ri-barcode-line"></i></span>
                                            </div>
                                        </div>
                                    </div>

                                    <!-- Lista de Productos en la Venta -->
                                    <div class="table-responsive">
                                        <table class="table" id="detalles_venta">
                                            <thead>
                                                <tr>
                                                    <th>Producto</th>
                                                    <th width="120px">Cantidad</th>
                                                    <th>Precio</th>
                                                    <th>Subtotal</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Panel Derecho - Total y Pago -->
                        <div class="col-md-4">
                            <div class="card">
                                <div class="card-body">
                                    <!-- Total -->
                                    <div class="mb-4 text-center">
                                        <h4 class="mb-1">Total a Pagar</h4>
                                        <h1 class="display-4 text-primary" id="total">S/ 0.00</h1>
                                    </div>

                                    <!-- Efectivo y Vuelto -->
                                    <div class="mb-3">
                                        <label class="form-label">Efectivo Recibido</label>
                                        <input type="number" id="efectivo" class="form-control form-control-lg" step="0.10">
                                    </div>

                                    <div class="mb-4 text-center">
                                        <h4>Vuelto</h4>
                                        <h2 class="text-success" id="vuelto">S/ 0.00</h2>
                                    </div>

                                    <!-- Botones de Acción -->
                                    <div class="d-grid gap-2">
                                        <button class="btn btn-primary btn-lg" id="btnpagar" disabled>
                                            <i class="ri-money-dollar-circle-line me-2"></i>COBRAR
                                        </button>
                                        <button class="btn btn-danger btn-lg" id="btncancelar">
                                            <i class="ri-close-circle-line me-2"></i>CANCELAR
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php require_once("../html/footer.php"); ?>
        </div>
    </div>

    <?php require_once("../html/js.php"); ?>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <link href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css" rel="stylesheet">
    <script type="text/javascript" src="ventarapida.js"></script>
</body>
</html>
<?php
    } else {
        header("Location:".Conectar::ruta()."view/404/");
    }
?>