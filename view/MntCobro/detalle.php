<?php
    require_once("../../config/conexion.php");
    require_once("../../models/Rol.php");
    $rol = new Rol();
    $datos = $rol->validar_menu_x_rol($_SESSION["ROL_ID"], "MntCobro");
    if (isset($_SESSION["USU_ID"]) AND count($datos) > 0) {
?>

<!doctype html>
<html lang="es" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none">
<head>
    <title>CompraVenta | Detalle Cobro</title>
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
                                <h4 class="mb-sm-0">Detalle Cobro</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Mantenimiento</a></li>
                                        <li class="breadcrumb-item"><a href="index.php">Cobro</a></li>
                                        <li class="breadcrumb-item active">Detalle</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Información de Cobro</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">ID Cobro:</label>
                                                <input type="text" class="form-control" id="cobro_id" name="cobro_id" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Venta:</label>
                                                <input type="text" class="form-control" id="venta_id" name="venta_id" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Cliente:</label>
                                                <input type="text" class="form-control" id="cli_nom" name="cli_nom" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label">Fecha:</label>
                                                <input type="text" class="form-control" id="cobro_fecha" name="cobro_fecha" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label">Método Pago:</label>
                                                <input type="text" class="form-control" id="pag_nom" name="pag_nom" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label">Moneda:</label>
                                                <input type="text" class="form-control" id="mon_nom" name="mon_nom" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label">Monto:</label>
                                                <input type="text" class="form-control" id="cobro_monto" name="cobro_monto" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-12">
                                            <div class="mb-3">
                                                <label class="form-label">Comentario:</label>
                                                <textarea class="form-control" id="cobro_comentario" name="cobro_comentario" rows="3" readonly></textarea>
                                            </div>
                                        </div>
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
    <script type="text/javascript" src="detalle.js"></script>
</body>

</html>
<?php
    } else {
        header("Location:".Conectar::ruta()."view/404/");
    }
?>