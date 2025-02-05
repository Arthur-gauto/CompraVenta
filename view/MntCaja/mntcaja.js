var suc_id = $('#SUC_IDx').val();

$(document).ready(function(){
    $('#table_data').DataTable({
        "aProcessing": true,
        "aServerSide": true,
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
        ],
        "ajax":{
            url:"../../controller/caja.php?op=listar",
            type:"post",
            data:{suc_id:suc_id},
        },
        "bDestroy": true,
        "responsive": true,
        "bInfo":true,
        "iDisplayLength": 10,
        "order": [[ 0, "desc" ]],
        "language": {
            "sProcessing":     "Procesando...",
            "sLengthMenu":     "Mostrar _MENU_ registros",
            "sZeroRecords":    "No se encontraron resultados",
            "sEmptyTable":     "Ningún dato disponible en esta tabla",
            "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix":    "",
            "sSearch":         "Buscar:",
            "sUrl":           "",
            "sInfoThousands":  ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst":    "Primero",
                "sLast":     "Último",
                "sNext":     "Siguiente",
                "sPrevious": "Anterior"
            },
            "oAria": {
                "sSortAscending":  ": Activar para ordenar la columna de manera ascendente",
                "sSortDescending": ": Activar para ordenar la columna de manera descendente"
            }
        },
    });
});

$(document).on("click","#btnnuevo", function(){
    let usu_id = $('#USU_IDx').val();
    
    Swal.fire({
        title: "¿Está seguro de abrir una nueva caja?",
        icon: "question",
        showCancelButton: true,
        confirmButtonText: "Si",
        cancelButtonText: "No",
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("../../controller/caja.php?op=guardaryeditar",
                {
                    suc_id: suc_id,
                    usu_id: usu_id,
                    caja_fecha: moment().format('YYYY-MM-DD')
                },
                function(data){
                    $('#table_data').DataTable().ajax.reload();
                    Swal.fire({
                        title: "¡Caja Abierta!",
                        text: "La caja se ha abierto correctamente",
                        icon: "success"
                    });
                }
            );
        }
    });
});

function ver(caja_id){
    window.location.href = 'detalle.php?c='+caja_id;
}

function eliminar(caja_id){
    Swal.fire({
        title: "¿Está seguro de cerrar la caja?",
        text: "Esta acción no se puede revertir",
        icon: "warning",
        showCancelButton: true,
        confirmButtonText: "Si",
        cancelButtonText: "No",
    }).then((result) => {
        if (result.isConfirmed) {
            $.post("../../controller/caja.php?op=eliminar",
                {
                    caja_id: caja_id
                },
                function(data){
                    $('#table_data').DataTable().ajax.reload();
                    Swal.fire({
                        title: "¡Caja Cerrada!",
                        text: "La caja se ha cerrado correctamente",
                        icon: "success"
                    });
                }
            );
        }
    });
}