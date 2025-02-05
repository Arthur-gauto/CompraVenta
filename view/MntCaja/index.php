<?php
    require_once("../../config/conexion.php");
    require_once("../../models/Rol.php");
    $rol = new Rol();
    $datos = $rol->validar_menu_x_rol($_SESSION["ROL_ID"], "MntCaja");
    if (isset($_SESSION["USU_ID"]) AND count($datos) > 0) {
?>

<!doctype html>
<html lang="es" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none">
<head>
    <title>CompraVenta | Mantenimiento Caja</title>
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
                                <h4 class="mb-sm-0">Mantenimiento Caja</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Mantenimiento</a></li>
                                        <li class="breadcrumb-item active">Caja</li>
                                    </ol>
                                </div>
                            </div>
                        </div>

                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-header">
                                    <button type="button" id="btnnuevo" class="btn btn-primary btn-label waves-effect waves-light"><i class="ri-user-smile-line label-icon align-middle fs-16 me-2"></i> Nuevo Registro</button>
                                </div>
                                <div class="card-body">
                                    <table id="table_data" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>ID</th>
                                                <th>Sucursal</th>
                                                <th>Usuario</th>
                                                <th>Fecha</th>
                                                <th>Ingresos</th>
                                                <th>Egresos</th>
                                                <th>Saldo</th>
                                                <th>Fecha Creación</th>
                                                <th>Estado</th>
                                                <th>Ver</th>
                                                <th>Cerrar</th>
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
    <!-- Agregar moment.js antes de mntcaja.js -->
    <script src="../../assets/libs/moment/moment.js"></script>
    <script type="text/javascript" src="mntcaja.js"></script>
</body>
</html>
<?php
    } else {
        header("Location:".Conectar::ruta()."view/404/");
    }
?>