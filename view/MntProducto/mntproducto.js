var suc_id = $('#SUC_IDx').val();

function init() {
    $("#mantenimiento_form").on("submit", function(e) {
        guardaryeditar(e);
    });
}

function guardaryeditar(e) {
    e.preventDefault();

    var formData = new FormData($("#mantenimiento_form")[0]);  
    formData.append('suc_id', $("#SUC_IDx").val());

    $.ajax({
        url: "../../controller/producto.php?op=guardaryeditar",
        type: "POST",
        data: formData,
        contentType: false,
        processData: false,
        success: function(data) {
            $('#modalmantenimiento').modal('hide');
            $('#table_data').DataTable().ajax.reload();

            swal.fire({
                title: 'Producto',
                text: 'El registro se guardó correctamente.',
                icon: 'success',
                customClass: {
                    confirmButton: 'btn-success'
                }
            });
        }
    });
}

$(document).ready(function() {
    // Cargar combos
    $.post("../../controller/categoria.php?op=combo", { suc_id: suc_id }, function(data) {
        $("#cat_id").html(data);
    });
    $.post("../../controller/moneda.php?op=combo", { suc_id: suc_id }, function(data) {
        $("#mon_id").html(data);
    });
    $.post("../../controller/unidad.php?op=combo", { suc_id: suc_id }, function(data) {
        $("#und_id").html(data);
    });

    // Manejar cambio de categoría para cargar subcategorías
    $("#cat_id").change(function() {
        var cat_id = $(this).val();
        if (cat_id) {
            $.post("../../controller/subcategoria.php?op=combo", { cat_id: cat_id }, function(data) {
                $("#scat_id").html(data);
            });
        } else {
            $("#scat_id").html('<option value="">Seleccione una subcategoría</option>');
        }
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
        "ajax": {
            url: "../../controller/producto.php?op=listar",
            type: "post",
            data: { suc_id: suc_id },
            error: function(xhr, error, thrown) {
                console.log('Error en DataTable:', error);
                console.log('Respuesta del servidor:', xhr.responseText);
            }
        },
        "bDestroy": true,
        "responsive": true,
        "bInfo": true,
        "iDisplayLength": 10,
        "order": [[0, "desc"]],
        "language": {
            "sProcessing": "Procesando...",
            "sLengthMenu": "Mostrar _MENU_ registros",
            "sZeroRecords": "No se encontraron resultados",
            "sEmptyTable": "Ningún dato disponible en esta tabla",
            "sInfo": "Mostrando registros del _START_ al _END_ de un total de _TOTAL_ registros",
            "sInfoEmpty": "Mostrando registros del 0 al 0 de un total de 0 registros",
            "sInfoFiltered": "(filtrado de un total de _MAX_ registros)",
            "sInfoPostFix": "",
            "sSearch": "Buscar:",
            "sUrl": "",
            "sInfoThousands": ",",
            "sLoadingRecords": "Cargando...",
            "oPaginate": {
                "sFirst": "Primero",
                "sLast": "Último",
                "sNext": "Siguiente",
                "sPrevious": "Anterior"
            }
        }
    });

    // Inicializar el formulario de mantenimiento
    init();
});

// Resto del código (editar, eliminar, etc.) permanece igual
function editar(prod_id) {
    $.post("../../controller/producto.php?op=mostrar", { prod_id: prod_id }, function(data) {
        data = JSON.parse(data);
        $("#prod_id").val(data.PROD_ID);
        $("#prod_nom").val(data.PROD_NOM);
        $("#prod_descrip").val(data.PROD_DESCRIP);
        $("#prod_pcompra").val(data.PROD_PCOMPRA);
        $("#prod_pventa").val(data.PROD_PVENTA);
        $("#prod_stock").val(data.PROD_STOCK);
        $("#cat_id").val(data.CAT_ID).trigger('change');

        $.post("../../controller/subcategoria.php?op=combo", { cat_id: data.CAT_ID }, function(subcat_data) {
            $("#scat_id").html(subcat_data);
            $("#scat_id").val(data.SCAT_ID).trigger('change');
            $("#und_id").val(data.UND_ID).trigger('change');
            $("#mon_id").val(data.MON_ID).trigger('change');
            $("#pre_imagen").html(data.PROD_IMG);
            $('#lbltitulo').html('Editar Registro');
            $('#modalmantenimiento').modal('show');
        });
    });
}

function eliminar(prod_id) {
    swal.fire({
        title: "ELIMINAR",
        text: "¿Desea eliminar el registro?",
        icon: "question",
        confirmButtonText: "Si",
        showCancelButton: true,
        cancelButtonText: "No",
    }).then((result) => {
        if (result.value) {
            $.post("../../controller/producto.php?op=eliminar", { prod_id: prod_id }, function(data) {});
            $('#table_data').DataTable().ajax.reload();
            swal.fire({
                title: 'Producto',
                text: 'Registro eliminado',
                icon: 'success',
                customClass: {
                    confirmButton: 'btn-danger'
                }
            });
        }
    });
}

$(document).on("click","#btnnuevo", function(){
    $("#prod_id").val('');
    $("#prod_nom").val('');
    $("#prod_descrip").val('');
    $("#prod_pcompra").val('');
    $("#prod_pventa").val('');
    $("#prod_stock").val('');
    $("#cat_id").val('').trigger('change');
    $("#scat_id").val('').trigger('change');
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

$(document).on("click", ".btn-editar", function () {
    let prodId = $(this).data("prod-id"); // Obtener el ID del producto
    var prod_id =$("#prod_id").val();
    console.log("Botón clickeado, prodId:", prod_id); // Para verificar
     // Asignar el ID al input hidden
    actualizarPreciosModal(prod_pcompra);
        $("#modalmantenimientolp").modal("show");
        $("#mantenimientolp_form").off("submit").on("submit", function (e) {
            e.preventDefault();
            var precioA = parseFloat($("#precio_A").val()) || prod_pcompra;
            var precioB = parseFloat($("#precio_B").val()) || prod_pcompra;
            var precioC = parseFloat($("#precio_C").val()) || prod_pcompra;

            $.post("../../controller/precio.php?op=guardar", {
                prod_id: prod_id,
                listp_a: precioA,
                listp_b: precioB,
                listp_c: precioC
            }, function (data) {
                console.log("Precios de venta actualizados:", data);
            });

            $("#modalmantenimientolp").modal("hide");
        });
});

function abrirModalListaPrecios(prod_id) {
    // Guardamos el ID del producto en un campo oculto del modal
    

    // Hacer una petición AJAX para obtener los datos de las listas de precios
    $.post("../../controller/producto.php?op=mostrar_listaprecio", { prod_id: prod_id }, function(data) {
        data = JSON.parse(data);
        $("#prod_id").val(prod_id);
        // Llenar los inputs del modal con los datos obtenidos
        $("#lista_precio_A").val(data.LISTP_A);
        $("#lista_precio_B").val(data.LISTP_B);
        $("#lista_precio_C").val(data.LISTP_C);
    });

    // Mostrar el modal de listas de precios
    $("#modalmantenimientolp").modal("show");
}
function actualizarPreciosModal(precioCompra) {
    $("#prod_pcompram").val(precioCompra);
    var prod_id = $("#prod_id").val();
    $.post("../../controller/precio.php?op=mostrar", { prod_id: prod_id }, function (data) {
        data = JSON.parse(data);
        if (data && data.LISTP_A) {
            $("#precio_A").val(data.LISTP_A);
            $("#precio_B").val(data.LISTP_B);
            $("#precio_C").val(data.LISTP_C);
        } else {
            $(".porcentaje-input").each(function () {
                let porcentaje = parseFloat($(this).val()) || 0;
                let nuevoPrecio = (precioCompra * (1 + porcentaje / 100)).toFixed(2);
                $(this).closest("tr").find(".precio-input").val(nuevoPrecio);
            });
        }
    });
}

function actualizarDesdePrecio(precioCompra) {
    $(".precio-input").each(function () {
        let precioFinal = parseFloat($(this).val()) || 0;

        if (precioCompra > 0 && precioFinal > 0) {
            let porcentaje = (((precioFinal / precioCompra) - 1) * 100).toFixed(0);
            $(this).closest("tr").find(".porcentaje-input").val(porcentaje);
        }
    });
}

function actualizarDesdePorcentaje(precioCompra) {
    $(".porcentaje-input").each(function () {
        let porcentaje = parseFloat($(this).val()) || 0;
        let nuevoPrecio = (precioCompra * (1 + porcentaje / 100)).toFixed(0);
        $(this).closest("tr").find(".precio-input").val(nuevoPrecio);
    });
}

$(document).on("input", ".precio-input", function () {
    let precioCompra = parseFloat($("#prod_pcompra").val()) || 0;
    actualizarDesdePrecio(precioCompra);
});

$(document).on("input", ".porcentaje-input", function () {
    let precioCompra = parseFloat($("#prod_pcompra").val()) || 0;
    actualizarDesdePorcentaje(precioCompra);
});

init();