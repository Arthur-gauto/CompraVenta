<?php
require_once("../../config/conexion.php");
require_once("../../models/Rol.php");
$rol = new Rol();
$datos = $rol->validar_acceso_rol($_SESSION["USU_ID"], "mntcobro");
if (isset($_SESSION["USU_ID"])) {
    if (is_array($datos) && count($datos) > 0) {
?>

<!doctype html>
<html lang="es" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none">
<head>
    <title>Arthur | Cobros a Crédito</title>
    <?php require_once("../html/head.php"); ?>
    <link rel="stylesheet" href="../../assets/css/cobro.css">
    <!-- Agregar CSS de Select2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
</head>
<body>
    <div id="layout-wrapper">
        <?php require_once("../html/header.php"); ?>
        <?php require_once("../html/menu.php"); ?>

        <div class="main-content">
            <div class="page-content container-fluid">
                <div class="header-section d-flex align-items-center justify-content-between mb-2">
                    <h1 class="title">Gestión de Cobros a Crédito</h1>
                </div>

                <div class="filters-section row mb-3">
                    <div class="col-md-4">
                        <label for="cli_id_filter" class="form-label">Filtrar por Cliente</label>
                        <select id="cli_id_filter" class="form-select select2">
                            <!-- Las opciones se cargarán dinámicamente mediante AJAX -->
                        </select>
                    </div>
                    <div class="col-md-4">
                        <label for="vent_id_filter" class="form-label">Filtrar por ID Venta</label>
                        <input type="number" id="vent_id_filter" class="form-control" placeholder="Ingrese ID Venta">
                    </div>
                </div>

                <div class="row">
                    <div class="col-lg-12">
                        <div class="card card-compact">
                            <div class="card-header">
                                <h4 class="card-title">Ventas Pendiente</h4>
                            </div>
                            <div class="card-body">
                                <table id="table_pendientes" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>ID Venta</th>
                                            <th>Cliente</th>
                                            <th>Monto Total</th>
                                            <th>Total Pagado</th>
                                            <th>Saldo Pendiente</th>
                                            <th>Fecha Venta</th>
                                            <th>Acción</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12">
                        <div class="card card-compact">
                            <div class="card-header">
                                <h4 class="card-title">Historial de Cobros</h4>
                            </div>
                            <div class="card-body">
                                <div id="resumen_cobro"></div>
                                <table id="table_historial" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                                    <thead>
                                        <tr>
                                            <th>ID Cobro</th>
                                            <th>Monto Pagado</th>
                                            <th>Fecha Pago</th>
                                            <th>Estado</th>
                                        </tr>
                                    </thead>
                                    <tbody></tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <?php require_once("../html/footer.php"); ?>
        </div>
    </div>

    <?php require_once("mantenimiento_cobro.php"); ?>
    <?php require_once("../html/js.php"); ?>
    <!-- Scripts en el orden correcto -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.5/js/dataTables.bootstrap5.min.js"></script>
    <script type="text/javascript" src="mntcobro.js"></script>
</body>
</html>

<?php
    } else {
        header("Location:" . Conectar::ruta() . "view/404/");
    }
} else {
    header("Location:" . Conectar::ruta() . "view/404/");
}
?>
