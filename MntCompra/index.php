<?php
    require_once("../../config/conexion.php");
    require_once("../../models/Rol.php");
    $rol = new rol();
    $datos= $rol -> validar_acceso_rol($_SESSION["USU_ID"],"mntcompra");
    if (isset($_SESSION["USU_ID"])){
        if(is_array($datos) and count($datos)>0){
?>
<!doctype html>
<html lang="en" data-layout="vertical" data-topbar="light" data-sidebar="dark" data-sidebar-size="lg" data-sidebar-image="none">
<head>
    <title>Compra | Sistema Profesional</title>
    <?php require_once("../html/head.php"); ?>
    <link rel="stylesheet" href="../../assets/css/venta.css"> <!-- Reusamos el mismo CSS, renómbralo si prefieres -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css">
</head>
<body>
    <div id="layout-wrapper">
        <?php require_once("../html/header.php"); ?>
        <?php require_once("../html/menu.php"); ?>

        <div class="main-content">
            <div class="page-content container-fluid">
                <div class="header-section d-flex align-items-center justify-content-between mb-2">
                    <h1 class="title">Nueva Compra</h1>
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="javascript: void(0);">Compras</a></li>
                        <li class="breadcrumb-item active">Crear</li>
                    </ol>
                </div>

                <!--TODO: ID DE COMPRA-->
                <input type="hidden" name="compr_id" id="compr_id">

                <div class="row g-2">
                    <!--TODO: DATOS DEL PAGO Y FACTURA-->
                    <div class="col-md-4">
                        <div class="card card-compact">
                            <div class="card-header">
                                <h4 class="card-title">Detalles Generales</h4>
                            </div>
                            <div class="card-body">
                                <div class="mb-2">
                                    <label for="fech_fact" class="form-label">Fecha</label>
                                    <input type="date" class="form-control" id="fech_fact" name="fech_fact" value="<?php echo date('Y-m-d'); ?>">
                                </div>
                                <div class="mb-2">
                                    <label for="nro_fact" class="form-label">Nro Factura</label>
                                    <input type="text" class="form-control" id="nro_fact" name="nro_fact" placeholder="Número Factura">
                                </div>
                                <button onclick="mostrarDiv()" class="btn btn-primary btn-sm w-100">
                                    <i class="ri-money-dollar-circle-line"></i> Configurar Pago
                                </button>
                                <div class="payment-options mt-2" id="tipoPagoDiv" hidden>
                                    <div class="row g-2">
                                        <div class="col-12">
                                            <label for="doc_id" class="form-label">Documento</label>
                                            <select id="doc_id" name="doc_id" class="form-select">
                                                <option value="1" selected>Seleccione</option>
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label for="pag_id" class="form-label">Pago</label>
                                            <select id="pag_id" name="pag_id" class="form-select">
                                                <option value="1" selected>Seleccione</option>
                                            </select>
                                        </div>
                                        <div class="col-12">
                                            <label for="mon_id" class="form-label">Moneda</label>
                                            <select id="mon_id" name="mon_id" class="form-select">
                                                <option value="1" selected>Seleccione</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!--TODO: DATOS DEL PROVEEDOR-->
                    <div class="col-md-8">
                        <div class="card card-compact">
                            <div class="card-header">
                                <h4 class="card-title">Proveedor</h4>
                            </div>
                            <div class="card-body">
                                <div class="row g-2">
                                    <div class="col-md-4">
                                        <label for="prov_id" class="form-label">Proveedor</label>
                                        <select id="prov_id" name="prov_id" class="form-select">
                                            <option value="0">Seleccionar proveedor</option>
                                        </select>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="prov_ruc" class="form-label">RUC</label>
                                        <input type="text" class="form-control" id="prov_ruc" name="prov_ruc" readonly>
                                        <input type="hidden" class="form-control" id="caj_id" name="caj_id">
                                        <input type="hidden" class="form-control" id="caj_egr" name="caj_egr">
                                    </div>
                                    <div class="col-md-4">
                                        <label for="prov_direcc" class="form-label">Dirección</label>
                                        <input type="text" class="form-control" id="prov_direcc" name="prov_direcc" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <!--TODO: DATOS DEL PRODUCTO-->
                <div class="card card-compact mt-2">
                    <div class="card-header">
                        <h4 class="card-title">Productos</h4>
                    </div>
                    <div class="card-body">
                        <div class="row g-2 mb-2 align-items-end">
                            <div class="col-md-3">
                                <label for="prod_id" class="form-label">Producto</label>
                                <select id="prod_id" name="prod_id" class="form-select"></select>
                            </div>
                            <div class="col-md-2">
                                <label for="cat_nom" class="form-label">Categoría</label>
                                <input type="text" id="cat_nom" name="cat_nom" class="form-control" readonly>
                            </div>
                            <div class="col-md-2">
                                <label for="prod_pcompra" class="form-label">Precio</label>
                                <input type="number" class="form-control" id="prod_pcompra" name="prod_pcompra" placeholder="0.00">
                            </div>
                            <div class="col-md-2">
                                <label for="detc_cant" class="form-label">Cant.</label>
                                <input type="number" class="form-control" id="detc_cant" name="detc_cant" value=1>
                            </div>
                            <div class="col-md-1">
                                <label for="prod_stock" class="form-label">Stock</label>
                                <input type="text" class="form-control" id="prod_stock" name="prod_stock" readonly>
                            </div>
                            <div class="col-md-1">
                                <label for="und_nom" class="form-label">Unidad</label>
                                <input type="text" class="form-control" id="und_nom" name="und_nom" readonly>
                            </div>
                            <div class="col-md-1 d-grid">
                                <button type="button" id="btnagregar" name="btnagregar" class="btn btn-primary btn-agregar">
                                    <i class="ri-add-line"></i>
                                </button>
                            </div>
                        </div>

                        <!-- Tabla con paginación -->
                        <table id="table_data" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="width: 5%;"></th>
                                    <th>Categoría</th>
                                    <th>Producto</th>
                                    <th>Unidad</th>
                                    <th>P. Compra</th>
                                    <th>Cantidad</th>
                                    <th>Total</th>
                                    <th style="width: 5%;"></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>

                        <!-- Comentario y totales/botones integrados -->
                        <div class="row g-2 mt-1 align-items-end">
                            <div class="col-md-7">
                                <label for="compr_coment" class="form-label">Comentario</label>
                                <textarea class="form-control" id="compr_coment" name="compr_coment" placeholder="Notas sobre la compra"></textarea>
                            </div>
                            <div class="col-md-5 d-flex align-items-end">
                                <div class="total-box me-2">
                                    <div class="total-item"><span>Sub Total:</span> <strong id="txtsubtotal">0</strong></div>
                                    <div class="total-item"><span>IVA (10%):</span> <strong id="txtigv">0</strong></div>
                                    <div class="total-item total"><span>Total:</span> <strong id="txttotal">0</strong></div>
                                </div>
                                <div class="d-flex flex-column gap-1">
                                    <button type="submit" id="btnguardar" class="btn btn-success">
                                        <i class="ri-save-line"></i> Guardar
                                    </button>
                                    <a id="btnlimpiar" class="btn btn-outline-secondary">
                                        <i class="ri-refresh-line"></i> Limpiar
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <?php require_once("../html/footer.php"); ?>
    </div>

    <?php require_once("mantenimiento.php"); ?>


    <?php require_once("../html/js.php"); ?>
    <script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.13.6/js/dataTables.bootstrap5.min.js"></script>
    <script type="text/javascript" src="mntcompra.js"></script>
    <script>
        $(document).ready(function() {
            $('#table_data').DataTable({
                "pageLength": 5, /* 5 productos por página */
                "lengthChange": false,
                "searching": false,
                "ordering": false,
                "info": false,
                "language": {
                    "paginate": {
                        "previous": "Anterior",
                        "next": "Siguiente"
                    }
                }
            });
        });
    </script>

</body>
</html>

<?php
        }else{
            header("Location:".Conectar::ruta()."view/404/");
        }
    }else{
        header("Location:".Conectar::ruta()."view/404/");
    }
?>