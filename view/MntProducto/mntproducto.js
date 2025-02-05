var suc_id = $('#SUC_IDx').val();
function init (){
    $("#mantenimiento_form").on("submit", function(e){
        
        guardaryeditar(e);
    });
}

function guardaryeditar(e){
    e.preventDefault();
    var formData = new FormData( $("#mantenimiento_form")[0]);

    formData.append('suc_id',$("#SUC_IDx").val())
    $.ajax({
        url:"../../controller/producto.php?op=guardaryeditar",
        type:"POST",
        data:formData,
        contentType:false,
        processData:false,
        success:function(data){
            $('#modalmantenimiento').modal('hide');  // Cierra el modal

            // Opcional: Recargar la tabla de datos (si es necesario)
            $('#table_data').DataTable().ajax.reload();

            // Opcional: Mensaje de éxito (puedes personalizarlo)
            swal.fire({
                title: 'Producto',
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
    $.post("../../controller/categoria.php?op=combo",{suc_id:suc_id},function(data){
        $("#cat_id").html(data);
    });
    $.post("../../controller/moneda.php?op=combo",{suc_id:suc_id},function(data){
        $("#mon_id").html(data);
    });
    $.post("../../controller/unidad.php?op=combo",{suc_id:suc_id},function(data){
        $("#und_id").html(data);
    });

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
            url:"../../controller/producto.php?op=listar",
            type:"post",
            data:{suc_id:suc_id},
            error: function (xhr, error, thrown) {
                console.log('Error en DataTable:', error);
                console.log('Respuesta del servidor:', xhr.responseText);
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
            }
        }
    });

});

function editar(prod_id){
    // Verifica que prod_id tiene el valor esperado
    
    $.post("../../controller/producto.php?op=mostrar",{prod_id:prod_id}, function(data)  {
        data=JSON.parse(data);
        $("#prod_id").val(data.PROD_ID);
        $("#prod_nom").val(data.PROD_NOM);
        $("#prod_descrip").val(data.PROD_DESCRIP);
        $("#prod_pcompra").val(data.PROD_PCOMPRA);
        $("#prod_pventa").val(data.PROD_PVENTA);
        $("#prod_stock").val(data.PROD_STOCK);
        $("#cat_id").val(data.CAT_ID).trigger('change');
        $("#und_id").val(data.UND_ID).trigger('change');
        $("#mon_id").val(data.MON_ID).trigger('change');
        $("#pre_imagen").html(data.PROD_IMG);
    });
    $('#lbltitulo').html('Editar Registro');
    $('#modalmantenimiento').modal('show');
    
}

function eliminar(prod_id){
    swal.fire({
        title:"ELIMINAR",
        text:"¿Desea eliminar el registro?",
        icon: "question",
        confirmButtonText: "Si",
        showCancelButton: true,
        cancelButtonText: "No",
    }).then((result)=>{
        if (result.value){
            $.post("../../controller/producto.php?op=eliminar",{prod_id:prod_id}, function(data)  {
            });

            $('#table_data').DataTable().ajax.reload();

            swal.fire({
                title:'Producto',
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
    $("#prod_id").val('');
    $("#prod_nom").val('');
    $("#prod_descrip").val('');
    $("#prod_pcompra").val('');
    $("#prod_pventa").val('');
    $("#prod_stock").val('');
    $("#cat_id").val('').trigger('change');
    $("#und_id").val('').trigger('change');
    $("#mon_id").val('').trigger('change');
    $('#lbltitulo').html('Nuevo Registro');
    $('#pre_imagen').html('<img src="../../assets/producto/no_imagen.png" class="rounded-circle avatar-xl img-thumbnail user-profile-image" alt="user-profile-image"></img><input type="hidden" name="hidden_producto_imagen" value="" />');
    $("#mantenimiento_form")[0].reset();
    $('#modalmantenimiento').modal('show');
});

function filePreview(input) {
    if (input.files && input.files[0]) {
        var reader = new FileReader();
        reader.onload = function (e) {
            $('#pre_imagen').html('<img src='+e.target.result+' class="rounded-circle avatar-xl img-thumbnail user-profile-image" alt="user-profile-image"></img>');
        }
        reader.readAsDataURL(input.files[0]);
    }
}

$(document).on('change','#prod_img',function(){
    filePreview(this);
});

$(document).on("click","#btnremovephoto", function(){
    $("#prod_img").val('');
    $("#pre_imagen").html('<img src="../../assets/producto/no_imagen.png" class="rounded-circle avatar-xl img-thumbnail user-profile-image" alt="user-profile-image"></img><input type="hidden" name="hidden_producto_imagen" value="" />');
});                                                                                                                                          
init();