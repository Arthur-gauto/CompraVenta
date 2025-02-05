$(document).ready(function(){
    var caja_id = getUrlParameter('c');

    // Cargar datos de la caja
    $.post("../../controller/caja.php?op=mostrar", { caja_id : caja_id }, function(data) {
        data = JSON.parse(data);
        $('#caja_id').val(data.CAJA_ID);
        $('#caja_fecha').val(data.CAJA_FECHA);
        $('#usu_nom').val(data.USU_NOM);
        $('#suc_nom').val(data.SUC_NOM);
        $('#caja_ingreso').val(data.CAJA_INGRESO);
        $('#caja_egreso').val(data.CAJA_EGRESO);
        $('#caja_saldo').val(data.CAJA_SALDO);
    });

    // Inicializar DataTable
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
            url: "../../controller/caja.php?op=listar_detalle",
            type: "post",
            data: { caja_id: caja_id },
            error: function(xhr, error, thrown){
                console.log('Error en DataTable:', error);
            }
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

// Función para obtener parámetros de la URL
var getUrlParameter = function getUrlParameter(sParam) {
    var sPageURL = decodeURIComponent(window.location.search.substring(1)),
        sURLVariables = sPageURL.split('&'),
        sParameterName,
        i;

    for (i = 0; i < sURLVariables.length; i++) {
        sParameterName = sURLVariables[i].split('=');

        if (sParameterName[0] === sParam) {
            return sParameterName[1] === undefined ? true : sParameterName[1];
        }
    }
};