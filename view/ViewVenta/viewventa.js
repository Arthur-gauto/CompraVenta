$(document).ready(function(){
    var vent_id = getUrlParameter('v');

    $.post("../../controller/venta.php?op=mostrar",{vent_id:vent_id},function(data){
        console.log(data);
        data=JSON.parse(data);
        $("#txtdirecc").html(data.EMP_DIRECC); 
        $("#txtemail").html(data.EMP_CORREO);
        $("#txttelf").html(data.EMP_TELF);
        $("#txtweb").html(data.EMP_PAG);
        $("#vent_id").html(data.VENT_ID);
        $("#fech_crea").html(data.FECH_CREA);
        $("#nro_factv").html(data.NRO_FACTV);
        $("#fech_factv").html(data.FECH_FACTV);
        $("#pag_nom").html(data.PAG_NOM);
        $("#txt_total").html(data.VENT_TOTAL);
        $("#txtruc").html(data.EMP_RUC);
        $("#vent_subtotal").html(data.VENT_SUBTOTAL);
        $("#vent_igv").html(data.VENT_IGV);
        $("#vent_total").html(data.VENT_TOTAL);
        $("#vent_coment").html(data.VENT_COMENT);
        $("#usu_nom").html(data.USU_NOM +' '+ data.USU_APE);
        $("#mon_nom").html(data.MON_NOM);
        $("#cli_nom").html("<b>Nombre: </b>&nbsp;&nbsp;&nbsp;"+data.CLI_NOM);
        $("#cli_ruc").html("<b>RUC: </b>&nbsp;&nbsp;&nbsp;"+data.CLI_RUC);
        $("#cli_direcc").html("<b>Dirección: </b>&nbsp;&nbsp;&nbsp;" + data.CLI_DIRECC);
        $("#cli_correo").html("<b>Correo: </b>&nbsp;&nbsp;&nbsp;"+data.CLI_CORREO);
        $("#nro_factv").html("<b>Nro de Factura: </b>&nbsp;&nbsp;&nbsp;" + data.NRO_FACTV);
        $("#fech_factv").html("<b>Fecha Facturación: </b>&nbsp;&nbsp;&nbsp;"+data.FECH_FACTV);

    });

    $.post("../../controller/venta.php?op=listardetalleformato",{vent_id:vent_id},function(data){
        $("#listdetalle").html(data);
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