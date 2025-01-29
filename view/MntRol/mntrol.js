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
        url:"../../controller/rol.php?op=guardaryeditar",
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
                title: 'Rol',
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
            url:"../../controller/rol.php?op=listar",
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
            "sLengthMenu":     "Mostrar _MENU_ registros",
            "sZeroRecords":    "No se encontraron resultados",
            "sEmptyTable":     "Ningún dato disponible en esta tabla",
            "sInfo":           "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty":      "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered":   "(filtrado de un total de _MAX_ registros)",
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

function editar(rol_id){
    // Verifica que rol_id tiene el valor esperado
    console.log("rol_id recibido en editar:", rol_id);
    
    $.post("../../controller/rol.php?op=mostrar",{rol_id:rol_id}, function(data)  {
        data=JSON.parse(data);
        console.log("Datos cargados desde el servidor:", data);
        $("#rol_id").val(data.ROL_ID);
        $("#rol_nom").val(data.ROL_NOM);
        
    });
    $('#lbltitulo').html('Editar Registro');
    $('#modalmantenimiento').modal('show');
    
}

function eliminar(rol_id){
    console.log(rol_id)
    swal.fire({
        title:"ELIMINAR",
        text:"¿Desea eliminar el registro?",
        icon: "question",
        confirmButtonText: "Si",
        showCancelButton: true,
        cancelButtonText: "No",
    }).then((result)=>{
        if (result.value){
            $.post("../../controller/rol.php?op=eliminar",{rol_id:rol_id}, function(data)  {
                console.log(data);
            });

            $('#table_data').DataTable().ajax.reload();

            swal.fire({
                title:'Rol',
                text: 'Registro eliminado',
                icon: 'success',
                customClass: {
                    confirmButton: 'btn-danger'  // Personalización del botón de confirmación para el error
                }
            })
        }
    })
}

function permiso(rol_id){

    $.post("../../controller/menu.php?op=insert",{rol_id:rol_id}, function(data)  {

    });

    $('#permisos_data').DataTable({
        "aProcessing": true,
        "aServerSide": true,
        dom: 'Bfrtip',
        buttons: [
            'copyHtml5',
            'excelHtml5',
            'csvHtml5',
        ],
        "ajax":{
            url:"../../controller/menu.php?op=listar",
            type:"post",
            data:{rol_id:rol_id}
        },
        "bDestroy": true,
        "responsive": true,
        "bInfo":true,
        "iDisplayLength": 15,
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
    $('#modalpermiso').modal('show');

}

function habilitar(mend_id){
    $.post("../../controller/menu.php?op=habilitar",{mend_id:mend_id}, function(data)  {
        $('#permisos_data').DataTable().ajax.reload();
    });
}

function deshabilitar(mend_id){
    $.post("../../controller/menu.php?op=deshabilitar",{mend_id:mend_id}, function(data)  {
        $('#permisos_data').DataTable().ajax.reload();
    });
}

$(document).on("click","#btnnuevo", function(){
    console.log("cad_id entró en btn nuevo", rol_id);
    $("#rol_id").val('');
    $("#rol_nom").val('');
    $('#lbltitulo').html('Nuevo Registro');
    $("#mantenimiento_form")[0].reset();
    $('#modalmantenimiento').modal('show');
});

init();