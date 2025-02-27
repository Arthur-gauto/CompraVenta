var emp_id = $('#EMP_IDx').val();
var suc_id = $('#SUC_IDx').val();
var usu_id = $('#USU_IDx').val();
var originalPrice = 0;  // Variable para almacenar el precio original
var listp_a = 0;
var listp_b = 0;
var listp_c = 0;

$(document).ready(function(){
    var nro_factv = $('#nro_factv').val();
    var fech_factv = $('#fech_factv').val();
    cargarProductos("");

    // Registrar nueva venta y obtener vent_id
    $.post("../../controller/venta.php?op=registrar", {suc_id: suc_id, usu_id: usu_id, nro_factv: nro_factv, fech_factv: fech_factv}, function(data) {
        data = JSON.parse(data);
        $("#vent_id").val(data.VENT_ID);
    });

    // Inicializar select2 para varios campos
    $('#cli_id').select2();
    $('#cat_id').select2();
    $('#prod_id').select2();
    $('#pag_id').select2();
    $('#mon_id').select2();
    $('#doc_id').select2();

    // Obtener datos de caja
    $.post("../../controller/caja.php?op=datoscaja", {suc_id: suc_id}, function(data) {
        data = JSON.parse(data);
        $("#caj_id").val(data.CAJ_ID);
    });

    // Cargar combo de documentos
    $.post("../../controller/documento.php?op=combo", {doc_tipo: "Venta"}, function(data) {
        $("#doc_id").html(data);
        $("#doc_id").val(4).trigger('change');
    });

    // Cargar combo de clientes
    $.post("../../controller/cliente.php?op=combo", {emp_id: emp_id}, function(data) {
        $("#cli_id").html(data);
    });

    // Cargar combo de categorías
    $.post("../../controller/categoria.php?op=combo", {suc_id: suc_id}, function(data) {
        $("#cat_id").html(data);
    });

    // Cargar combo de pagos
    $.post("../../controller/pago.php?op=combo", function(data) {
        $("#pag_id").html(data);
        $("#pag_id").val(1).trigger('change');
    });

    // Cargar combo de monedas
    $.post("../../controller/moneda.php?op=combo", {suc_id: suc_id}, function(data) {
        $("#mon_id").html(data);
        $("#mon_id").val(1).trigger('change');
    });

    // Cambiar datos del cliente al seleccionar uno
    $("#cli_id").change(function() {
        $("#cli_id").each(function() {
            cli_id = $(this).val();
            $.post("../../controller/cliente.php?op=mostrar", {cli_id: cli_id}, function(data) {
                data = JSON.parse(data);
                $("#cli_ruc").val(data.CLI_RUC);
                $("#cli_direcc").val(data.CLI_DIRECC);
                $("#cli_telf").val(data.CLI_TELF);
                $("#cli_correo").val(data.CLI_CORREO);
            });
        });
    });

    // Cambiar datos de subcategorías al seleccionar una categoría
    $("#cat_id").change(function() {
        $("#cat_id").each(function() {
            cat_id = $(this).val();
            $.post("../../controller/producto.php?op=combo", {cat_id: cat_id}, function(data) {
                $("#prod_id").html(data);
            });
        });
    });

    // Buscar productos por nombre
    $("#buscar_prod").on("input", function() {
        var prod_nom = $(this).val();
        cargarProductos(prod_nom);
    });

    // Función para cargar productos
    function cargarProductos(prod_nom) {
        $.post("../../controller/producto.php?op=buscar_producto", { prod_nom: prod_nom }, function(data) {
            $("#prod_id").html(data);
        });
    }

    // Cambiar datos del producto al seleccionar uno
    $('#prod_id').change(function() {
        var prod_id = $(this).val();
        if (prod_id === '') {
            $("#cat_nom").val('');
            $("#prod_pcompra").val('');
            $("#prod_stock").val('');
            $("#und_nom").val('');
            $("#cat_id").val('');
            return;
        }
        $.post("../../controller/producto.php?op=mostrar", { prod_id: prod_id }, function(data) {
            data = JSON.parse(data);
            if (data.error) {
                swal.fire({
                    title: 'Error',
                    text: data.error,
                    icon: 'error'
                });
            } else {
                $("#cat_nom").val(data.CAT_NOM);
                originalPrice = parseFloat(data.PROD_PCOMPRA);
                listp_a = parseFloat(data.LISTP_A) || 0;
                listp_b = parseFloat(data.LISTP_B) || 0;
                listp_c = parseFloat(data.LISTP_C) || 0;
                // Mostrar el precio minorista por defecto
                $("#prod_pventa").val(listp_a.toFixed(0));
                $("#prod_stock").val(data.PROD_STOCK);
                $("#und_nom").val(data.UND_NOM);
                $("#cat_id").val(data.CAT_ID);
            }
        });
    });

    // Evento para actualizar el precio de venta basado en la lista de precios seleccionada
    $("#pro_list").change(function() {
        var selectedOption = $(this).val();
        var price = 0;

        if (selectedOption === 'listp_a') {
            price = listp_a;
        } else if (selectedOption === 'listp_b') {
            price = listp_b;
        } else if (selectedOption === 'listp_c') {
            price = listp_c;
        }

        if (!isNaN(price)) {
            $("#prod_pventa").val(price.toFixed(0));
        }
    });

    // Evento para actualizar el precio original si el usuario lo cambia manualmente
    $("#prod_pventa").on('input', function() {
        originalPrice = parseFloat($(this).val());
    });
});

// Función para mostrar u ocultar el div de tipo de pago
function mostrarDiv() {
    let div = document.getElementById("tipoPagoDiv");
    div.hidden = !div.hidden; // Alterna entre oculto y visible
}

// Evento para agregar un producto a la venta
$(document).on("click", "#btnagregar", function() {
    var vent_id = $("#vent_id").val();
    var prod_id = $("#prod_id").val();
    var prod_pventa = $("#prod_pventa").val();
    var detv_cant = $("#detv_cant").val();

    if ($("#prod_id").val() == '' || $("#prod_pventa").val() == '' || $("#detv_cant").val() == '') {
        swal.fire({
            title: 'Venta',
            text: 'Error. Campos vacíos',
            icon: 'error'
        });
    } else {
        $.post("../../controller/venta.php?op=guardardetalle", {
            vent_id: vent_id, 
            prod_id: prod_id,
            prod_pventa: prod_pventa,
            detv_cant: detv_cant
        }, function(data) {
            console.log(data);
        });

        $.post("../../controller/venta.php?op=calculo", {vent_id: vent_id}, function(data) {
            console.log(data);
            data = JSON.parse(data);
            $("#txtsubtotal").html(data.VENT_SUBTOTAL).addClass('highlight');
            $("#txtigv").html(data.VENT_IGV).addClass('highlight');
            $("#txttotal").html(data.VENT_TOTAL).addClass('highlight');
            setTimeout(() => { $("#txtsubtotal, #txtigv, #txttotal").removeClass('highlight'); }, 1000);
        });

        $("#prod_pventa").val('');
        $("#detv_cant").val('');
        listar(vent_id);
    }
});

// Función para eliminar un detalle de venta
function eliminar(detv_id, vent_id) {
    swal.fire({
        title: "ELIMINAR",
        text: "¿Desea eliminar el registro?",
        icon: "question",
        confirmButtonText: "Sí",
        showCancelButton: true,
        cancelButtonText: "No",
    }).then((result) => {
        if (result.value) {
            $.post("../../controller/venta.php?op=eliminardetalle", {detv_id: detv_id}, function(data) {
                console.log(data);
            });

            $.post("../../controller/venta.php?op=calculo", {vent_id: vent_id}, function(data) {
                console.log(data);
                data = JSON.parse(data);
                $("#txtsubtotal").html(data.VENT_SUBTOTAL).addClass('highlight');
                $("#txtigv").html(data.VENT_IGV).addClass('highlight');
                $("#txttotal").html(data.VENT_TOTAL).addClass('highlight');
                setTimeout(() => { $("#txtsubtotal, #txtigv, #txttotal").removeClass('highlight'); }, 1000);
            });

            listar(vent_id);

            swal.fire({
                title: 'Venta',
                text: 'Registro eliminado',
                icon: 'success',
                customClass: {
                    confirmButton: 'btn-danger'
                }
            });
        }
    });
}

// Función para listar detalles de venta
function listar(vent_id) {
    $('#table_data').DataTable({
        "aProcessing": true,
        "aServerSide": true,
        dom: 'frtip', // Sin botones de exportación
        "ajax": {
            url: "../../controller/venta.php?op=listardetalle",
            type: "post",
            data: {vent_id: vent_id}
        },
        "bDestroy": true,
        "responsive": true,
        "bInfo": false,
        "iDisplayLength": 5,
        "order": [[ 0, "desc" ]],
        "language": {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar MENU registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando registros del START al END de un total de TOTAL registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "(filtrado de un total de MAX registros)",
            "sInfoPostFix": "",
            "sSearch": "Buscar:",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending": ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        }
    });
}

// Verificar si la caja está abierta al cargar la página
document.addEventListener("DOMContentLoaded", function() {
    verificarCajaAbierta();
});

// Función para verificar si la caja está abierta
function verificarCajaAbierta() {
    var suc_id = $('#SUC_IDx').val();
    fetch('../../controller/venta.php?op=verificarcaja', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `accion=verificarcaja&suc_id=${suc_id}`
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            swal.fire({
                title: 'Atención',
                text: data.message,
                icon: 'info',
                confirmButtonText: 'Ok',
                customClass: {
                    confirmButton: 'btn-info'
                },
                buttonsStyling: false,
                confirmButtonClass: 'btn btn-info',
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then(function(result) {
                if (result.isConfirmed) {
                    window.location.href = '../MntCaja/index.php';
                }
            });
        } else {
            swal.fire({
                title: 'Error',
                text: data.message,
                icon: 'error',
                confirmButtonText: 'Ok',
                customClass: {
                    confirmButton: 'btn-danger'
                },
                buttonsStyling: false,
                confirmButtonClass: 'btn btn-danger',
                allowOutsideClick: false,
                allowEscapeKey: false
            }).then(function(result) {
                if (result.isConfirmed) {
                    event.preventDefault();
                    return false;
                }
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        return false;
    });
}

// Evento para guardar la venta
$(document).on("click","#btnguardar", function(){
    var vent_id = $("#vent_id").val();
    var doc_id = $("#doc_id").val();
    var pag_id = $("#pag_id").val();
    var cli_id = $("#cli_id").val();
    var cli_ruc = $("#cli_ruc").val();
    var cli_direcc = $("#cli_direcc").val();
    var cli_correo = $("#cli_correo").val();
    var vent_coment = $("#vent_coment").val();
    var mon_id = $("#mon_id").val();
    var nro_factv = $("#nro_factv").val();
    var fech_factv = $("#fech_factv").val();
    var caj_id = $("#caj_id").val();

    if (nro_factv.trim() === '' || fech_factv.trim() === '' || doc_id === '0' || pag_id === '0' || cli_id === '0' || mon_id === '0') {
        swal.fire({
            title: 'Venta',
            text: 'Error. Campos vacíos',
            icon: 'error'
        });
    } else {
        $.post("../../controller/venta.php?op=calculo", {vent_id: vent_id}, function(data) {
            data = JSON.parse(data);
            if(data.VENT_TOTAL == null) {
                swal.fire({
                    title: 'Venta',
                    text: 'Error. No existe detalle',
                    icon: 'error'
                });
            } else {
                $.post("../../controller/venta.php?op=guardar", {
                    vent_id: vent_id,
                    pag_id: pag_id,
                    cli_id: cli_id,
                    cli_ruc: cli_ruc,
                    cli_direcc: cli_direcc,
                    cli_correo: cli_correo,
                    vent_coment: vent_coment,
                    mon_id: mon_id,
                    doc_id: doc_id,
                    nro_factv: nro_factv,
                    fech_factv: fech_factv,
                    caj_id: caj_id
                }, function(data) {
                    swal.fire({
                        title: 'Venta',
                        text: 'Registrado correctamente con Nro: V-' + vent_id,
                        icon: 'success',
                        footer: "<a href='../ViewVenta/?v="+vent_id+"' target='_blank'>Desea ver el documento</a>"
                    });
                });
            }
        });
    }
});

// Evento para limpiar el formulario
$(document).on("click","#btnlimpiar", function(){
    location.reload();
});