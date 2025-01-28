<?php
    require_once("../../config/conexion.php");
    if (isset($_SESSION["USU_ID"])){
?>

<!doctype html>
<html lang="es" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none">

<head>

    <title>Arthur | Listado de venta</title>
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
                                <h4 class="mb-sm-0">Listado de venta</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Listado</a></li>
                                        <li class="breadcrumb-item active">Ventas</li>
                                    </ol>
                                </div>  
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="card">
                                <div class="card-body">
                                    <table id="table_data" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                                        <thead>
                                            <tr>
                                                <th>Nro</th>
                                                <th>RUC</th>
                                                <th>Cliente</th>
                                                <th>Pago</th>
                                                <th>Moneda</th>
                                                <th>Subtotal</th>
                                                <th>Iva</th>
                                                <th>Total</th>
                                                <th>Usuario</th>
                                                <th></th>
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
                 

                </div>

            </div>


            <?php require_once("../html/footer.php"); ?>
            
        </div>
        
    </div>

    <?php require_once("mantenimiento.php"); ?>

    <?php require_once("../html/js.php"); ?>
    <script type="text/javascript" src="listventa.js"></script>
    

    
</body>

</html>
<?php
    }else{
        header("Location:".Conectar::ruta()."view/404/");
    }
?>