$(document).ready(function(){
    var cobro_id = getUrlParameter('c');

    $.post("../../controller/cobro.php?op=mostrar", { cobro_id : cobro_id }, function(data) {
        data = JSON.parse(data);
        $('#cobro_id').val(data.COBRO_ID);
        $('#venta_id').val(data.VENTA_ID);
        $('#cli_nom').val(data.CLI_NOM);
        $('#cobro_fecha').val(data.COBRO_FECHA);
        $('#pag_nom').val(data.PAG_NOM);
        $('#mon_nom').val(data.MON_NOM);
        $('#cobro_monto').val(data.COBRO_MONTO);
        $('#cobro_comentario').val(data.COBRO_COMENTARIO);
    });
});

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