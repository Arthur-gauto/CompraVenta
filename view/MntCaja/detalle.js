$(document).ready(function(){
    var caj_id = getUrlParameter('c');

    // Cargar datos de la caja
    $.post("../../controller/caja.php?op=mostrarcaj", { caj_id : caj_id }, function(data) {
        data = JSON.parse(data);
        $('#caj_id').val(data.CAJ_ID);
        $('#fech_crea').val(data.FECH_CREA);
        $('#usu_nom').val(data.USU_NOM);
        $('#suc_nom').val(data.SUC_NOM);
        $('#caj_ing').val(data.CAJ_ING);
        $('#caj_egr').val(data.CAJ_EGR);
        $('#caj_ini').val(data.CAJ_INI);
        $('#caj_fin').val(data.CAJ_FIN);
    });

    $.post("../../controller/caja.php?op=calculoegr", {caj_id: caj_id}, function(data) {
        data = JSON.parse(data);
        $("#caj_egr").val(data.COMPR_TOTAL);
        console.log($("#caj_egr").val());
        var caj_egr = $("#caj_egr").val(); 
        $.post("../../controller/caja.php?op=editaregr", {
            caj_id: caj_id,
            caj_egr: caj_egr
            
        }, function(data){
            console.log(caj_id,caj_egr);
            console.log("Correcto");
            calcularCajFin()
        });
    });

    

    $.post("../../controller/caja.php?op=calculoing", {caj_id: caj_id}, function(data) {
        data = JSON.parse(data);
        $("#caj_ing").val(data.VENT_TOTAL);
        console.log($("#caj_ing").val());
        var caj_ing = $("#caj_ing").val(); 
        $.post("../../controller/caja.php?op=editaring", {
            caj_id: caj_id,
            caj_ing: caj_ing
            
        }, function(data){
            console.log(caj_id,caj_ing);
            console.log("Correcto");
            calcularCajFin()
        });
    });
    

    function calcularCajFin() {
        let caj_ini = parseFloat($("#caj_ini").val()) || 0;
        let caj_ing = parseFloat($("#caj_ing").val()) || 0;
        let caj_egr = parseFloat($("#caj_egr").val()) || 0;

        let caj_fin = caj_ini + caj_ing - caj_egr;
        $("#caj_fin").val(caj_fin);

    }

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
            url: "../../controller/caja.php?op=listardetalle",
            type: "post",
            data: { caj_id: caj_id },
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