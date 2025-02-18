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

    formData.set('caj_id', $("#caj_id").val());

    formData.append('suc_id',$("#SUC_IDx").val())
    $.ajax({
        url:"../../controller/gasto.php?op=guardaryeditar",
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
                title: 'Gasto',
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
            url:"../../controller/gasto.php?op=listar",
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

    $.post("../../controller/caja.php?op=datoscaja", {suc_id: suc_id}, function(data) {
        data = JSON.parse(data);
        $("#caj_id").val(data.CAJ_ID);
        console.log($("#caj_id").val());
    });

});

function editar(gas_id){
    // Verifica que gas_id tiene el valor esperado
    console.log("gas_id recibido en editar:", gas_id);
    
    $.post("../../controller/gasto.php?op=mostrar",{gas_id:gas_id}, function(data)  {
        data=JSON.parse(data);
        console.log("Datos cargados desde el servidor:", data);
        $("#gas_id").val(data.GAS_ID);
        $("#gas_descrip").val(data.GAS_DESCRIP);
        $("#gas_mon").val(data.GAS_MON);
        
    });
    $('#lbltitulo').html('Editar Registro');
    $('#modalmantenimiento').modal('show');
    
}

function eliminar(gas_id){
    console.log(gas_id)
    swal.fire({
        title:"ELIMINAR",
        text:"¿Desea eliminar el registro?",
        icon: "question",
        confirmButtonText: "Si",
        showCancelButton: true,
        cancelButtonText: "No",
    }).then((result)=>{
        if (result.value){
            $.post("../../controller/gasto.php?op=eliminar",{gas_id:gas_id}, function(data)  {
                console.log(data);
            });

            $('#table_data').DataTable().ajax.reload();

            swal.fire({
                title:'Gasto',
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
    console.log("cad_id entró en btn nuevo", gas_id);
    $("#gas_id").val('');
    $("#gas_descrip").val('');
    $("#gas_tipo").val('').trigger('change');
    $("#gas_mon").val('');
    $('#lbltitulo').html('Nuevo Registro');
    $("#mantenimiento_form")[0].reset();
    $('#modalmantenimiento').modal('show');
});

init();