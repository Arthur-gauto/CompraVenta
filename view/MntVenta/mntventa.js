var emp_id = $('#EMP_IDx').val();
var suc_id = $('#SUC_IDx').val();
var usu_id = $('#USU_IDx').val();
var originalPrice = 0;
var listp_a = 0;
var listp_b = 0;
var listp_c = 0;
var caj_id = null;

function cargarCaja() {
    return new Promise((resolve, reject) => {
        $.post("../../controller/caja.php?op=datoscaja", {suc_id: suc_id}, function(data) {
            console.log("Respuesta de caja.php?op=datoscaja:", data);
            try {
                data = JSON.parse(data);
                if (data && data.CAJ_ID) {
                    $("#caj_id").val(data.CAJ_ID);
                    caj_id = data.CAJ_ID;
                    console.log("CAJ_ID seteado:", caj_id);
                    $("#btnguardar").prop('disabled', false);
                    resolve(caj_id);
                } else {
                    console.warn("No hay caja abierta:", data.message);
                    $("#caj_id").val('');
                    caj_id = null;
                    $("#btnguardar").prop('disabled', true);
                    swal.fire({
                        title: 'Atención',
                        text: data.message || 'No hay una caja abierta. Por favor, abre una caja antes de continuar.',
                        icon: 'warning',
                        confirmButtonText: 'Ir a abrir caja',
                        showCancelButton: true,
                        cancelButtonText: 'Ignorar'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            window.location.href = '../MntCaja/index.php';
                        }
                    });
                    reject(new Error("No hay caja abierta"));
                }
            } catch (e) {
                console.error("Error al parsear respuesta de caja.php:", e, "Datos recibidos:", data);
                $("#caj_id").val('');
                caj_id = null;
                $("#btnguardar").prop('disabled', true);
                swal.fire('Error', 'Error al cargar datos de la caja', 'error');
                reject(e);
            }
        }).fail(function(xhr, status, error) {
            console.error("Error al cargar datos de caja:", error);
            $("#caj_id").val('');
            caj_id = null;
            $("#btnguardar").prop('disabled', true);
            swal.fire('Error', 'No se pudo conectar con el servidor para verificar la caja', 'error');
            reject(new Error("Fallo en la solicitud AJAX"));
        });
    });
}

function limpiarFormulario() {
    var nro_factv = $('#nro_factv').val();
    var fech_factv = $('#fech_factv').val();
    $.post("../../controller/venta.php?op=registrar", {suc_id: suc_id, usu_id: usu_id, nro_factv: nro_factv, fech_factv: fech_factv}, function(data) {
        data = JSON.parse(data);
        $("#vent_id").val(data.VENT_ID);
    });

    $("#cli_id").val('0').trigger('change');
    $("#cli_ruc").val('');
    $("#cli_direcc").val('');
    $("#cli_telf").val('');
    $("#cli_correo").val('');
    $("#prod_id").val('').trigger('change');
    $("#cat_nom").val('');
    $("#prod_pventa").val('');
    $("#detv_cant").val('');
    $("#prod_stock").val('');
    $("#und_nom").val('');
    $("#vent_coment").val('');
    $("#nro_factv").val('');
    $("#fech_factv").val(fechaActual); // Usar variable global definida en mntventa.php
    $("#doc_id").val('4').trigger('change');
    $("#pag_id").val('1').trigger('change');
    $("#mon_id").val('1').trigger('change');
    $("#txtsubtotal").html('0');
    $("#txtigv").html('0');
    $("#txttotal").html('0');
    $('#table_data').DataTable().clear().draw();
}

function cargarProductos(prod_nom) {
    $.post("../../controller/producto.php?op=buscar_producto", { prod_nom: prod_nom }, function(data) {
        $("#prod_id").html(data);
    });
}

$(document).ready(function() {
    var nro_factv = $('#nro_factv').val();
    var fech_factv = $('#fech_factv').val() || fechaActual; // Usar fechaActual como fallback
    cargarProductos("");

    $("#btnguardar").prop('disabled', true);

    $.post("../../controller/venta.php?op=registrar", {suc_id: suc_id, usu_id: usu_id, nro_factv: nro_factv, fech_factv: fech_factv}, function(data) {
        data = JSON.parse(data);
        $("#vent_id").val(data.VENT_ID);
    });

    $('#cli_id').select2();
    $('#cat_id').select2();
    $('#prod_id').select2();
    $('#pag_id').select2();
    $('#mon_id').select2();
    $('#doc_id').select2();

    cargarCaja();

    $.post("../../controller/documento.php?op=combo", {doc_tipo: "Venta"}, function(data) {
        $("#doc_id").html(data);
        $("#doc_id").val(4).trigger('change');
    });

    $.post("../../controller/cliente.php?op=combo", {emp_id: emp_id}, function(data) {
        $("#cli_id").html(data);
    });

    $.post("../../controller/categoria.php?op=combo", {suc_id: suc_id}, function(data) {
        $("#cat_id").html(data);
    });

    $.post("../../controller/pago.php?op=combo", function(data) {
        $("#pag_id").html(data);
        $("#pag_id").val(1).trigger('change');
    });

    $.post("../../controller/moneda.php?op=combo", {suc_id: suc_id}, function(data) {
        $("#mon_id").html(data);
        $("#mon_id").val(1).trigger('change');
    });

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

    $("#cat_id").change(function() {
        $("#cat_id").each(function() {
            cat_id = $(this).val();
            $.post("../../controller/producto.php?op=combo", {cat_id: cat_id}, function(data) {
                $("#prod_id").html(data);
            });
        });
    });

    $("#buscar_prod").on("input", function() {
        var prod_nom = $(this).val();
        cargarProductos(prod_nom);
    });

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
                $("#prod_pventa").val(listp_a.toFixed(0));
                $("#prod_stock").val(data.PROD_STOCK);
                $("#und_nom").val(data.UND_NOM);
                $("#cat_id").val(data.CAT_ID);
            }
        });
    });

    $("#pro_list").change(function() {
        var selectedOption = $(this).val();
        var price = 0;
        if (selectedOption === 'listp_a') price = listp_a;
        else if (selectedOption === 'listp_b') price = listp_b;
        else if (selectedOption === 'listp_c') price = listp_c;
        if (!isNaN(price)) $("#prod_pventa").val(price.toFixed(0));
    });

    $("#prod_pventa").on('input', function() {
        originalPrice = parseFloat($(this).val());
    });

    $("#pag_id").change(function() {
        var pag_id = $(this).val();
        $("#credito_field").toggle(pag_id == "2");
    });
});

function mostrarDiv() {
    let div = document.getElementById("tipoPagoDiv");
    div.hidden = !div.hidden;
}

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

        if ($("#cobro_pagado").val()>=0){
            $.post("../../controller/venta.php?op=calculo", {vent_id: vent_id}, function(data) {
                console.log(data);
                data = JSON.parse(data);
                $("#txtsubtotal").html(data.VENT_SUBTOTAL).addClass('highlight');
                $("#txtigv").html(data.VENT_IGV).addClass('highlight');
                $("#txttotal").html($("#cobro_pagado").val()).addClass('highlight');
                setTimeout(() => { $("#txtsubtotal, #txtigv, #txttotal").removeClass('highlight'); }, 1000);
            });
        }else{

            $.post("../../controller/venta.php?op=calculo", {vent_id: vent_id}, function(data) {
                console.log(data);
                data = JSON.parse(data);
                $("#txtsubtotal").html(data.VENT_SUBTOTAL).addClass('highlight');
                $("#txtigv").html(data.VENT_IGV).addClass('highlight');
                $("#txttotal").html(data.VENT_TOTAL).addClass('highlight');
                setTimeout(() => { $("#txtsubtotal, #txtigv, #txttotal").removeClass('highlight'); }, 1000);
            });
        }
        $("#prod_pventa").val('');
        $("#detv_cant").val('');
        listar(vent_id);
    }
});

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

function listar(vent_id) {
    $('#table_data').DataTable({
        "aProcessing": true,
        "aServerSide": true,
        dom: 'frtip',
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

document.addEventListener("DOMContentLoaded", function() {
    verificarCajaAbierta();
});

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
                customClass: { confirmButton: 'btn-info' },
                buttonsStyling: false,
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
                customClass: { confirmButton: 'btn-danger' },
                buttonsStyling: false,
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

$(document).on("click", "#btnguardar", function() {
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
    var cobro_pagado = pag_id == "2" ? ($("#cobro_pagado").val() || 0) : 0;

    console.log("Valor de caj_id antes de guardar:", caj_id);

    if (nro_factv.trim() === '' || fech_factv.trim() === '' || doc_id === '0' || pag_id === '0' || cli_id === '0' || mon_id === '0' || !caj_id) {
        swal.fire({
            title: 'Venta',
            text: 'Error. Campos vacíos o caja no definida. Por favor, asegúrate de que haya una caja abierta.',
            icon: 'error',
            confirmButtonText: 'Ir a abrir caja',
            showCancelButton: true,
            cancelButtonText: 'Ignorar'
        }).then((result) => {
            if (result.isConfirmed) {
                window.location.href = '../MntCaja/index.php';
            }
        });
        return;
    }

    var formData = new FormData();
    formData.append("vent_id", vent_id);
    formData.append("doc_id", doc_id);
    formData.append("pag_id", pag_id);
    formData.append("cli_id", cli_id);
    formData.append("cli_ruc", cli_ruc);
    formData.append("cli_direcc", cli_direcc);
    formData.append("cli_correo", cli_correo);
    formData.append("vent_coment", vent_coment);
    formData.append("mon_id", mon_id);
    formData.append("nro_factv", nro_factv);
    formData.append("fech_factv", fech_factv);
    formData.append("caj_id", caj_id);
    formData.append("cobro_pagado", cobro_pagado);

    $.ajax({
        url: "../../controller/venta.php?op=guardar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(data) {
            try {
                data = JSON.parse(data);
                if (data.success) {
                    swal.fire({
                        title: pag_id == "2" ? 'Venta a Crédito' : 'Venta',
                        text: data.message,
                        icon: 'success',
                        confirmButtonText: 'OK',
                        footer: "<a href='../ViewVenta/?v=" + vent_id + "' target='_blank'>Desea ver el documento</a>"
                    }).then(() => {
                        limpiarFormulario();
                    });
                } else {
                    swal.fire('Error', data.message, 'error');
                }
            } catch (e) {
                console.error("Error al parsear JSON:", e, "Respuesta:", data);
                swal.fire('Error', 'Respuesta inválida del servidor', 'error');
            }
        },
        error: function(xhr, status, error) {
            console.error("Error al guardar venta:", error);
            swal.fire('Error', 'Error al procesar la venta', 'error');
        }
    });
});

$(document).on("click", "#btnlimpiar", function() {
    limpiarFormulario();
});