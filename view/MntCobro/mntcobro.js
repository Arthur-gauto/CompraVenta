function init() {
    $("#pago_form").on("submit", function(e) {
        registrarPago(e);
    });
}

function registrarPago(e) {
    e.preventDefault();
    var formData = new FormData($("#pago_form")[0]);
    var vent_id = $("#vent_id_pago").val();
    var cobro_pagado = parseInt($("#cobro_pagado_pago").val());

    console.log("Datos del formulario:", Array.from(formData.entries()));
    console.log("cobro_pagado ingresado:", cobro_pagado);

    if (isNaN(cobro_pagado) || cobro_pagado <= 0) {
        Swal.fire('Error', 'El monto a pagar debe ser mayor a 0.', 'error');
        return;
    }

    $.post("../../controller/cobro.php?op=listar", { vent_id: vent_id }, function(data) {
        try {
            data = JSON.parse(data);
            var saldoPendiente = parseInt(data.resumen.SaldoPendiente);
            console.log("Saldo pendiente:", saldoPendiente);

            if (cobro_pagado > saldoPendiente) {
                Swal.fire('Error', 'El monto a pagar no puede exceder el saldo pendiente: ' + saldoPendiente, 'error');
                return;
            }

            $.ajax({
                url: "../../controller/cobro.php?op=registrar_pago",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                success: function(data) {
                    try {
                        data = JSON.parse(data);
                        if (data.success) {
                            $('#modalpago').modal('hide');
                            $('#table_pendientes').DataTable().ajax.reload();
                            $('#table_historial').DataTable().ajax.reload();
                            Swal.fire('Cobro', data.message, 'success');
                        } else {
                            Swal.fire('Error', data.message, 'error');
                        }
                    } catch (e) {
                        console.error("Error al parsear respuesta de registro:", e, "Respuesta:", data);
                        Swal.fire('Error', 'Respuesta inválida del servidor al registrar el pago.', 'error');
                    }
                },
                error: function(xhr, status, error) {
                    console.error("Error al registrar pago:", error);
                    Swal.fire('Error', 'No se pudo registrar el pago.', 'error');
                }
            });
        } catch (e) {
            console.error("Error al parsear saldo pendiente:", e, "Respuesta:", data);
            Swal.fire('Error', 'No se pudo verificar el saldo pendiente.', 'error');
        }
    });
}

$(document).ready(function() {
    $.post("../../controller/cliente.php?op=combo", { emp_id: 1 }, function(data) {
        $("#cli_id_filter").html(data).select2({
            placeholder: "Seleccione un cliente",
            allowClear: true
        });
    }).fail(function(xhr, status, error) {
        console.error("Error al cargar combo de clientes:", error);
    });

    var tablePendientes = $('#table_pendientes').DataTable({
        "aProcessing": true,
        "aServerSide": true,
        dom: 'Bfrtip',
        buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5'],
        "ajax": {
            url: "../../controller/cobro.php?op=listar_pendientes",
            type: "post",
            data: function(d) {
                d.cli_id = $("#cli_id_filter").val();
            },
            error: function(xhr, status, error) {
                console.error("Error al cargar pendientes:", error);
            }
        },
        "columns": [
            { "data": "VENT_ID" },
            { "data": "CLI_NOM" },
            { "data": "VENT_TOTAL" },
            { "data": "TotalPagado" },
            { "data": "SaldoPendiente" },
            { "data": "FECH_FACTV" },
            { "data": null, "render": function(data) {
                return '<button type="button" class="btn btn-success btn-icon btn-cobrar" data-vent_id="' + data.VENT_ID + '"><i class="ri-money-dollar-circle-line"></i></button>' +
                       '&nbsp;<button type="button" class="btn btn-info btn-icon btn-historial" data-vent_id="' + data.VENT_ID + '"><i class="ri-eye-line"></i></button>';
            }}
        ],
        "bDestroy": true,
        "responsive": true,
        "bInfo": true,
        "iDisplayLength": 10,
        "order": [[0, "desc"]],
        "language": {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "No hay ventas a crédito pendientes",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sSearch": "Buscar:",
            "sLoadingRecords": "Cargando..."
        },
        "searching": false // Deshabilitar la búsqueda en la tabla
    });

    var tableHistorial = $('#table_historial').DataTable({
        "aProcessing": true,
        "aServerSide": true,
        dom: 'Bfrtip',
        buttons: ['copyHtml5', 'excelHtml5', 'csvHtml5'],
        "ajax": {
            url: "../../controller/cobro.php?op=listar",
            type: "post",
            data: function(d) {
                d.vent_id = $("#vent_id_filter").val() || '';
            },
            error: function(xhr, status, error) {
                console.error("Error al cargar historial:", error);
            }
        },
        "columns": [
            { "data": "COBRO_ID" },
            { "data": "COBRO_PAGADO" },
            { "data": "FECH_CREA" },
            { "data": "EST", "render": function(data) {
                return data == 'Pagado' ? "Pagado" : (data == 'Parcial' ? "Parcial" : "Pendiente");
            }}
        ],
        "bDestroy": true,
        "responsive": true,
        "bInfo": true,
        "iDisplayLength": 10,
        "order": [[0, "desc"]],
        "language": {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron pagos",
            "sEmptyTable": "No hay pagos asociados a esta venta",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sSearch": "Buscar:",
            "sLoadingRecords": "Cargando..."
        },
        "searching": false, // Deshabilitar la búsqueda en la tabla
        "drawCallback": function(settings) {
            var resumen = settings.json && settings.json.resumen ? settings.json.resumen : null;
            if (resumen) {
                $("#resumen_cobro").html(
                    `<p><strong>Total:</strong> ${resumen.VENT_TOTAL} |
                    <strong>Pagado:</strong> ${resumen.TotalPagado} |
                    <strong>Saldo:</strong> ${resumen.SaldoPendiente} |
                    <strong>Estado:</strong> ${resumen.Estado}</p>`
                );
            } else {
                $("#resumen_cobro").html("<p>No hay datos de cobro para esta venta.</p>");
            }
        }
    });

    $("#cli_id_filter").on("change", function() {
        tablePendientes.ajax.reload();
    });

    $("#vent_id_filter").on("change", function() {
        tableHistorial.ajax.reload();
    });

    $(document).on("click", ".btn-cobrar", function() {
        var vent_id = $(this).data("vent_id");
        $("#vent_id_filter").val(vent_id); // Opcional: Actualiza el campo de filtro
        tableHistorial.ajax.reload(); // Recarga el historial
        cobrar(vent_id);
    });

    $(document).on("click", ".btn-historial", function() {
        var vent_id = $(this).data("vent_id");
        $("#vent_id_filter").val(vent_id); // Actualiza el campo de filtro
        tableHistorial.ajax.reload(); // Recarga el historial con el VENT_ID seleccionado
    });
});

function cobrar(vent_id) {
    $.post("../../controller/cobro.php?op=mostrar", { vent_id: vent_id }, function(data) {
        try {
            data = JSON.parse(data);
            if (data.success === false) {
                Swal.fire('Error', data.message, 'error');
                return;
            }
            $("#vent_id_pago").val(data.VENT_ID);
            $("#cli_id_pago").val(data.CLI_ID);
            $("#cobro_pagado_pago").val('');
            $("#saldo_pendiente_pago").text("Saldo Pendiente: " + (data.SaldoPendiente || "N/A"));
            $('#modalpago').modal('show');
        } catch (e) {
            console.error("Error al parsear JSON:", e, "Respuesta:", data);
            Swal.fire('Error', 'Respuesta inválida del servidor al intentar cargar los datos de cobro.', 'error');
        }
    }).fail(function(xhr, status, error) {
        console.error("Error al cargar datos de cobro:", error);
        Swal.fire('Error', 'No se pudo conectar con el servidor para cargar los datos de cobro.', 'error');
    });
}

init();
