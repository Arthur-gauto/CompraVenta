var suc_id = $('#SUC_IDx').val();
var usu_id = $('#USU_IDx').val();
function init (){
    $("#mantenimiento_form").on("submit", function(e){
        
        guardaryeditar(e);
    });
}

function guardaryeditar(e) {
    e.preventDefault();
    var formData = new FormData($("#mantenimiento_form")[0]);

    formData.append('suc_id', $("#SUC_IDx").val());
    formData.append('usu_id', $("#USU_IDx").val());

    $.ajax({
        url: "../../controller/caja.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        dataType: "json",
        success: function(response) {
            console.log("Respuesta del servidor:", response);

            if (response.status === "error") {
                Swal.fire({
                    title: "Error",
                    text: response.message, // Mostrará el mensaje "Ya existe una caja abierta."
                    icon: "error",
                    confirmButtonText: "Aceptar"
                });
            } else {
                Swal.fire({
                    title: "Caja",
                    text: response.message,
                    icon: "success"
                }).then(() => {
                    $('#modalmantenimiento').modal('hide');
                    $('#table_data').DataTable().ajax.reload();
                });
            }
        },
        error: function(xhr, status, error) {
            console.error("Error en AJAX:", xhr.responseText);
            Swal.fire({
                title: "Error",
                text: "No se pudo abrir la caja. Inténtelo nuevamente.",
                icon: "error"
            });
        }
    });
}



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
            data:{suc_id:suc_id}
        },
        "bDestroy": true,
        "responsive": true,
        "bInfo":true,
        "iDisplayLength": 10,
        "order": [[ 0, "desc" ]],
        "language": {
            "sProcessing":     "Procesando...",
            "sLengthMenu":     "Mostrar MENU registros",
            "sZeroRecords":    "No se encontraron resultados",
            "sEmptyTable":     "Ningún dato disponible en esta tabla",
            "sInfo":           "Mostrando registros del START al END de un total de TOTAL registros",
            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered":   "(filtrado de un total de MAX registros)",
            "sInfoPostFix":    "",
            "sSearch":         "Buscar:",
            "sUrl":            "",
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

function editar(caj_id){
    // Verifica que caj_id tiene el valor esperado
    console.log("caj_id recibido en editar:", caj_id);
    
    $.post("../../controller/caja.php?op=mostrar",{caj_id:caj_id}, function(data)  {
        data=JSON.parse(data);
        console.log("Datos cargados desde el servidor:", data);
        $("#caj_id").val(data.CAJ_ID);
        $("#usu_nom").val(data.USU_NOM);
        
    });
    $('#lbltitulo').html('Editar Registro');
    $('#modalmantenimiento').modal('show');
    
}

function eliminar(caj_id){
    console.log(caj_id)
    swal.fire({
        title:"ELIMINAR",
        text:"¿Desea Cerrar La Caja?",
        icon: "question",
        confirmButtonText: "Si",
        showCancelButton: true,
        cancelButtonText: "No",
    }).then((result)=>{
        if (result.value){
            $.post("../../controller/caja.php?op=eliminar",{caj_id:caj_id}, function(data)  {
                console.log(data);
            });

            $('#table_data').DataTable().ajax.reload();

            swal.fire({
                title:'Caja',
                text: 'Registro eliminado',
                icon: 'success',
                customClass: {
                    confirmButton: 'btn-danger'  // Personalización del botón de confirmación para el error
                }
            })
        }
    })
}

function ver(caj_id) {
    console.log("Abriendo en nueva ventana: detalle.php?c=" + caj_id); // Para depuración
    window.open('detalle.php?c=' + caj_id, '_blank');
}


$(document).on("click","#btnnuevo", function(){
    console.log("cad_id entró en btn nuevo", caj_id);
    $("#caj_id").val('');
    $("#caj_ini").val('');
    $('#lbltitulo').html('Abrir caja');
    $("#mantenimiento_form")[0].reset();
    $('#modalmantenimiento').modal('show');
});



init();