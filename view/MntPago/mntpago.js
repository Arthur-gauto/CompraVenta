var suc_id = $('#SUC_IDx').val();

function init() {
    $("#mantenimiento_form").on("submit", function(e) {
        guardaryeditar(e);
    });
}

function guardaryeditar(e) {
    e.preventDefault();
    var formData = new FormData($("#mantenimiento_form")[0]);
    console.log("Datos enviados:", Array.from(formData.entries()));

    formData.append('suc_id', $("#SUC_IDx").val());
    $.ajax({
        url: "../../controller/pagocuota.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(data) {
            console.log(data);
            $('#modalmantenimiento').modal('hide');  // Cierra el modal

            // Recargar la tabla de datos
            $('#table_data').DataTable().ajax.reload();

            // Mensaje de éxito
            swal.fire({
                title: 'Pago',
                text: 'El registro se guardó correctamente.',
                icon: 'success',
                customClass: {
                    confirmButton: 'btn-success'  // Personalización del botón de confirmación
                }
            });
        }
    });
}

$(document).ready(function() {
    $('#table_data').DataTable({
        "aProcessing": true,
        "aServerSide": true,
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
        ],
        "ajax": {
            url: "../../controller/pagocuota.php?op=listar",
            type: "post",
            data: { suc_id: suc_id }
        },
        "bDestroy": true,
        "responsive": true,
        "bInfo": true,
        "iDisplayLength": 10,
        "order": [[0, "desc"]],
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
        },
    });

    // Evento para abrir el modal y rellenar los campos
    $('#modalmantenimiento').on('show.bs.modal', function(event) {
        var button = $(event.relatedTarget); // Botón que abrió el modal
        var cuota_id = button.data('cuota_id'); // Extraer la información de los atributos data-*
        var cuota_pendiente = button.data('cuota_pendiente');
        var fecha_vencimiento = button.data('fecha_vencimiento');

        var modal = $(this);
        modal.find('.modal-title').text('Detalles del Pago');
        modal.find('#cuota_id').val(cuota_id);
        modal.find('#cuota_pendiente').val(cuota_pendiente);
        modal.find('#fecha_vencimiento').val(fecha_vencimiento);
    });
});

function editar(cuota_id) {
    console.log("cuota_id recibido en editar:", cuota_id);

    $.post("../../controller/pagocuota.php?op=mostrar", { cuota_id: cuota_id }, function(data) {
        try {
            data = JSON.parse(data);
            console.log("Datos cargados desde el servidor:", data);
            $("#cuota_id").val(data.CUOTA_ID);
            $("#cuota_pendiente").val(data.CUOTA_PENDIENTE);
            $("#fecha_vencimiento").val(data.FECHA_VENCIMIENTO);
            $("#cuota_pagada").val(data.CUOTA_PAGADA);
        } catch (e) {
            console.error("Error al analizar JSON:", e);
            console.error("Respuesta del servidor:", data);
        }
    });
    $('#lbltitulo').html('Editar Registro');
    $('#modalmantenimiento').modal('show');
}

function eliminar(cuota_id) {
    console.log(cuota_id);
    swal.fire({
        title: "ELIMINAR",
        text: "¿Desea eliminar el registro?",
        icon: "question",
        confirmButtonText: "Si",
        showCancelButton: true,
        cancelButtonText: "No",
    }).then((result) => {
        if (result.value) {
            $.post("../../controller/pagocuota.php?op=eliminar", { cuota_id: cuota_id }, function(data) {
                console.log(data);
            });

            $('#table_data').DataTable().ajax.reload();

            swal.fire({
                title: 'Pago',
                text: 'Registro eliminado',
                icon: 'success',
                customClass: {
                    confirmButton: 'btn-danger'  // Personalización del botón de confirmación para el error
                }
            });
        }
    });
}

$(document).on("click", "#btnnuevo", function() {
    $("#cuota_id").val('');
    $("#cuota_pendiente").val('');
    $("#fecha_vencimiento").val('');
    $("#cuota_pagada").val('');
    $('#lbltitulo').html('Nuevo Registro');
    $("#mantenimiento_form")[0].reset();
    $('#modalmantenimiento').modal('show');
});

init();