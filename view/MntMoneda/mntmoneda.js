var suc_id = $('#SUC_IDx').val();
function init (){
    $("#mantenimiento_form").on("submit", function(e){
        
        guardaryeditar(e);
    });
}

function guardaryeditar(e){
    e.preventDefault();
    var formData = new FormData( $("#mantenimiento_form")[0]);
    console.log("Datos enviados:", Array.from(formData.entries()));

    formData.append('suc_id',$("#SUC_IDx").val())
    $.ajax({
        url:"../../controller/moneda.php?op=guardaryeditar",
        type:"POST",
        data:formData,
        contentType:false,
        processData:false,
        success:function(data){
            console.log(data);
            $('#modalmantenimiento').modal('hide');  // Cierra el modal

            // Opcional: Recargar la tabla de datos (si es necesario)
            $('#table_data').DataTable().ajax.reload();

            // Opcional: Mensaje de éxito (puedes personalizarlo)
            swal.fire({
                title: 'Moneda',
                text: 'El registro se guardó correctamente.',
                icon: 'success',
                customClass: {
                    confirmButton: 'btn-success'  // Personalización del botón de confirmación
                }
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
            url:"../../controller/moneda.php?op=listar",
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

function editar(mon_id){
    // Verifica que mon_id tiene el valor esperado
    console.log("mon_id recibido en editar:", mon_id);
    
    $.post("../../controller/moneda.php?op=mostrar",{mon_id:mon_id}, function(data)  {
        data=JSON.parse(data);
        console.log("Datos cargados desde el servidor:", data);
        $("#mon_id").val(data.MON_ID);
        $("#mon_nom").val(data.MON_NOM);
        
    });
    $('#lbltitulo').html('Editar Registro');
    $('#modalmantenimiento').modal('show');
    
}

function eliminar(mon_id){
    console.log(mon_id)
    swal.fire({
        title:"ELIMINAR",
        text:"¿Desea eliminar el registro?",
        icon: "question",
        confirmButtonText: "Si",
        showCancelButton: true,
        cancelButtonText: "No",
    }).then((result)=>{
        if (result.value){
            $.post("../../controller/moneda.php?op=eliminar",{mon_id:mon_id}, function(data)  {
                console.log(data);
            });

            $('#table_data').DataTable().ajax.reload();

            swal.fire({
                title:'Moneda',
                text: 'Registro eliminado',
                icon: 'success',
                customClass: {
                    confirmButton: 'btn-danger'  // Personalización del botón de confirmación para el error
                }
            })
        }
    })
}

$(document).on("click","#btnnuevo", function(){
    console.log("cad_id entró en btn nuevo", mon_id);
    $("#mon_id").val('');
    $("#mon_nom").val('');
    $('#lbltitulo').html('Nuevo Registro');
    $("#mantenimiento_form")[0].reset();
    $('#modalmantenimiento').modal('show');
});

init();