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

    <title>Página inicial | Compra</title>
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
                                <h4 class="mb-sm-0">Nueva Compra</h4>

                                <div class="page-title-right">
                                    <ol class="breadcrumb m-0">
                                        <li class="breadcrumb-item"><a href="javascript: void(0);">Compra</a></li>
                                        <li class="breadcrumb-item active">Nueva compra</li>
                                    </ol>
                                </div>

                            </div>
                        </div>
                        <!--TODO: ID DE COMPRA-->
                        <input type="hidden" name="compr_id" id="compr_id">

                        <!--TODO: DATOS DEL PAGO-->
                        <div>
                        <button onclick="mostrarDiv()" class="btn btn-success waves-effect waves-light ri-money-dollar-circle-line"></button>
                        </div>
                        <div class="row" id="tipoPagoDiv" hidden>
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header align-items-center d-flex">
                                        <h4 class="card-title mb-0 flex-grow-1">Tipo de pago</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="live-preview">
                                            <div class="row align-items-center g-3">
                                                
                                                <div class="col-lg-4">
                                                    <label for="doc_id" class="form-label">Documento</label>
                                                        <select id="doc_id" name="doc_id" class="form-control form-select" aria-label=".form-select-sm example">
                                                            <option value="1" selected>Seleccione</option>
                                                            
                                                        </select>
                                                </div>                                    

                                                <div class="col-lg-4">
                                                    <label for="valueInput" class="form-label">Pago</label>
                                                        <select id="pag_id" name="pag_id" class="form-control form-select" aria-label=".form-select-sm example">
                                                            <option value="1" selected>Seleccione</option>
                                                            
                                                        </select>
                                                </div>
                                                
                                                <div class="col-lg-4">
                                                    <label for="valueInput" class="form-label">Moneda</label>
                                                        <select id="mon_id" name="mon_id" class="form-control form-select" aria-label=".form-select-sm example">
                                                            <option value="1" selected>Seleccione</option>
                                                            
                                                        </select>
                                                </div>

                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div>
                        </div>
                        <!--TODO: DATOS DEL PROVEEDOR-->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header align-items-center d-flex">
                                        <h4 class="card-title mb-0 flex-grow-1">Datos del Proveedor</h4>
                                    </div>
                                    <div class="card-body">
                                        <div class="live-preview">
                                            <div class="row align-items-center g-3">
                                                <div class="col-lg-4">
                                                    <div>
                                                        <label for="exampleInputdate" class="form-label">Fecha</label>
                                                        <input type="date" class="form-control" id="fech_fact" name="fech_fact" 
                                                        value="<?php echo date('Y-m-d'); ?>"> <!-- Aquí asignamos la fecha en formato yyyy-mm-dd -->
                                                    </div>
                                                </div>
                                                <div class="col-lg-4">
                                                    <label for="valueInput" class="form-label">Nro Factura</label>
                                                    <input type="text" class="form-control" id="nro_fact" name="nro_fact" placeholder="Número Factura.">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <div class="live-preview">
                                            <div class="row align-items-center g-3">
                                                <div class="col-lg-4">
                                                <label for="valueInput" class="form-label">Proveedor </label>
                                                    <select id="prov_id" name="prov_id" class="form-control form-select" aria-label=".form-select-sm example">
                                                        <option value="0" selected>Seleccione</option>
                                                        
                                                    </select>
                                                </div>
                                                <div class="col-lg-4">
                                                <label for="valueInput" class="form-label">RUC: </label>
                                                <input type="text" class="form-control" id="prov_ruc" name="prov_ruc" readonly>
                                                </div>
                                                <div class="col-lg-4">
                                                <label for="valueInput" class="form-label">Dirección: </label>
                                                <input type="text" class="form-control" id="prov_direcc" name="prov_direcc" readonly>
                                                </div>
                                            </div>
                                        </div>
                                        
                                    </div>
                                </div>
                            </div> 
                        </div>
                        <!--TODO: DATOS DEL PRODUCTO-->
                        <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header align-items-center d-flex">
                                        <h4 class="card-title mb-0 flex-grow-1">Agregar producto</h4>
                                    </div><!-- end card header -->
                                    <div class="card-body">
                                        <div class="live-preview">
                                            <div class="row align-items-center g-3">
                                                <div class="col-lg-3">
                                                    <label for="valueInput" class="form-label">Categoria</label>
                                                        <select id="cat_id" name="cat_id" class="form-control form-select" aria-label=".form-select-sm example">
                                                            <option selected>Seleccione</option>
                                                            
                                                        </select>
                                                </div>
                                                <div class="col-lg-3">
                                                    <label for="valueInput" class="form-label">Producto</label>
                                                    <select id="prod_id" name="prod_id" class="form-control form-select" aria-label=".form-select-sm example">
                                                            <option selected>Seleccione</option>
                                                    </select> 
                                                </div>
                                                <div class="col-lg-1">
                                                    <label for="valueInput" class="form-label">Precio</label>
                                                    <input type="number" class="form-control" id="prod_pcompra" name="prod_pcompra" placeholder="Precio " >
                                                </div>
                                                <div class="col-lg-1">
                                                    <label for="valueInput" class="form-label">Stock</label>
                                                    <input type="text" class="form-control" id="prod_stock" name="prod_stock" placeholder="Stock " readonly>
                                                </div>
                                                <div class="col-lg-2">
                                                    <label for="valueInput" class="form-label">Unidad de medida</label>
                                                    <input type="text" class="form-control" id="und_nom" name="und_nom" placeholder="Unidad de Medida " readonly>
                                                </div>
                                                <div class="col-lg-1">
                                                    <label for="valueInput" class="form-label">Cantidad</label>
                                                    <input type="number" class="form-control" id="detc_cant" name="detc_cant" placeholder="Cant. ">
                                                </div>
                                                <div class="col-lg-1 d-grid gap-0">
                                                    <label for="valueInput" class="form-label">&nbsp;</label>
                                                    
                                                    <button type="button" id="btnagregar" name="btnagregar" class="btn btn-primary waves-effect waves-light ri-add-circle-line"></button>
                                                </div>
                                            </div>
                                        </div>
                                        <br>
                                        <br>
                                        <table id="table_data" class="table table-bordered dt-responsive nowrap table-striped align-middle" style="width:100%">
                                            <thead>
                                                <tr>
                                                    <th></th>
                                                    <th>Categoria</th>
                                                    <th>Producto</th>
                                                    <th>Und</th>
                                                    <th>P. Compra</th>
                                                    <th>Cantidad</th>
                                                    <th>Total</th>
                                                    <th></th>
                                                </tr>
                                            </thead>
                                            <tbody>
                                                
                                            </tbody>
                                        </table>
                                        <table id="table_data2" class="table table-borderless table-nowrap align-middle mb-0 ms-auto" style="width:250px">
                                                        <tbody>
                                                            <tr>
                                                                <td>Sub Total</td>
                                                                <td class="text-end" id="txtsubtotal">0</td>
                                                            </tr>
                                                            <tr>
                                                                <td>IVA (10%)</td>
                                                                <td class="text-end" id="txtigv">0</td>
                                                            </tr>
                                                            
                                                            
                                                            <tr class="border-top border-top-dashed fs-15">
                                                                <th scope="row">Total </th>
                                                                <th class="text-end" id="txttotal">0</th>
                                                            </tr>
                                                        </tbody>
                                        </table>
                                        <div class="mt-4">
                                                <label for="compr_coment" class="form-label text-muted text-uppercase fw-semibold">Comentario</label>
                                                <textarea class="form-control alert alert-info" id="compr_coment" placeholder="Comentario" rows="2" required=""></textarea>
                                        </div>
                                        <div class="hstack gap-2 left-content-end d-print-none mt-4">
                                                <button type="submit" id= "btnguardar" class="btn btn-success"><i class="ri-save-line align-bottom me-1"></i> Guardar</button>
                                                <a id="btnlimpiar" class="btn btn-warning"><i class="ri-brush-2-line align-bottom me-1"></i> Limpiar</a>
                                        </div>


                                    </div>
                                </div>
                            </div> <!-- end col -->
                        </div>
                        <!-- end row -->
                        <!--TODO: DETALLE DE COMPRA-->
                        <!-- <div class="row">
                            <div class="col-lg-12">
                                <div class="card">
                                    <div class="card-header align-items-center d-flex">
                                        <h4 class="card-title mb-0 flex-grow-1">Agregar producto</h4>
                                    </div>
                                    <div class="card-body">
                                        
                                    </div>
                                </div>
                            </div> 
                        </div> -->
                        
                    </div>

                </div>
            </div>

            <?php require_once("../html/footer.php"); ?>
        </div>

    </div>


    <?php require_once("../html/js.php"); ?>
    
    <script type="text/javascript" src = "mntcompra.js"></script>
    

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