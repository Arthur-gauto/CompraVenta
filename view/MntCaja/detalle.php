<?php
    require_once("../../config/conexion.php");
    require_once("../../models/Rol.php");
    $rol = new Rol();
    $datos = $rol->validar_acceso_rol($_SESSION["ROL_ID"], "MntCaja");
    if (isset($_SESSION["USU_ID"]) AND count($datos) > 0) {
?>

<!doctype html>
<html lang="es" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none">
<head>
    <title>CompraVenta | Detalle Caja</title>
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
                                <h4 class="mb-sm-0">Detalle Caja</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Mantenimiento</a></li>
                                        <li class="breadcrumb-item"><a href="index.php">Caja</a></li>
                                        <li class="breadcrumb-item active">Detalle</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Información de Caja</h5>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label">ID Caja:</label>
                                                <input type="text" class="form-control" id="caj_id" name="caj_id" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label">Fecha:</label>
                                                <input type="text" class="form-control" id="fech_crea" name="fech_crea" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label">Usuario:</label>
                                                <input type="text" class="form-control" id="usu_nom" name="usu_nom" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-3">
                                            <div class="mb-3">
                                                <label class="form-label">Sucursal:</label>
                                                <input type="text" class="form-control" id="suc_nom" name="suc_nom" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Ingresos:</label>
                                                <input type="text" class="form-control" id="caj_ing" name="caj_ing" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Egresos:</label>
                                                <input type="text" class="form-control" id="caj_egr" name="caj_egr" readonly>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <div class="mb-3">
                                                <label class="form-label">Saldo:</label>
                                                <input type="text" class="form-control" id="caj_fin" name="caj_fin" readonly>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <h5 class="card-title mb-0">Detalle de Movimientos</h5>
                                </div>
                                <div class="card-body">
                                <table id="table_data" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>ID</th>
                                            <th>Tipo</th>
                                            <th>Referencia</th>
                                            <th>Fecha</th>
                                            <th>Método Pago</th>
                                            <th>Moneda</th>
                                            <th>Monto</th>
                                            <th>Comentario</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                    </tbody>
                                </table>
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