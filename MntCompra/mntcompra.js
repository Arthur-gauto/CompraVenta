var emp_id= $('#EMP_IDx').val();
var suc_id= $('#SUC_IDx').val();
var usu_id= $('#USU_IDx').val();

$(document).ready(function(){
    var nro_fact = $('#nro_fact').val();  
    var fech_fact = $('#fech_fact').val(); 
    cargarProductos("");
    $.post("../../controller/compra.php?op=registrar", {suc_id: suc_id, usu_id:usu_id,nro_fact: nro_fact,fech_fact: fech_fact}, function(data) {
        data=JSON.parse(data);
        $("#compr_id").val(data.COMPR_ID);
    });
    
    $('#prov_id').select2();
    
    $('#cat_id').select2();

    $('#prod_id').select2();
    
    $('#pag_id').select2();

    $('#mon_id').select2();

    $('#doc_id').select2();

    $.post("../../controller/caja.php?op=datoscaja", {suc_id: suc_id}, function(data) {
        data = JSON.parse(data);
        $("#caj_id").val(data.CAJ_ID);
        console.log();
    });

    

    $.post("../../controller/documento.php?op=combo", {doc_tipo: "compra"}, function(data) {
        $("#doc_id").html(data);
        $("#doc_id").val(1).trigger('change');
    });
    

    $.post("../../controller/proveedor.php?op=combo", {emp_id: emp_id}, function(data) {
        $("#prov_id").html(data);
    });
    
    

    $.post("../../controller/categoria.php?op=combo", {suc_id: suc_id}, function(data) {
        $("#cat_id").html(data);
    });

    $.post("../../controller/pago.php?op=combo",  function(data) {
        $("#pag_id").html(data);
        $("#pag_id").val(1).trigger('change');
    });

    $.post("../../controller/moneda.php?op=combo", {suc_id: suc_id}, function(data) {
        $("#mon_id").html(data);
        $("#mon_id").val(1).trigger('change');
    });


    $("#buscar_prod").on("input", function () {
        var prod_nom = $(this).val();
        cargarProductos(prod_nom);
    });

    function cargarProductos(prod_nom) {
        $.post("../../controller/producto.php?op=buscar_producto", { prod_nom: prod_nom }, function (data) {
            $("#prod_id").html(data);
        });
    }

    $('#prod_id').change(function () {
        var prod_id = $(this).val();
        if (prod_id === '') {
            $("#cat_nom").val('');
            $("#prod_pcompra").val('');
            $("#prod_stock").val('');
            $("#und_nom").val('');
            $("#cat_id").val('');
            return;
        }
        $.post("../../controller/producto.php?op=mostrar", { prod_id: prod_id }, function (data) {
            data = JSON.parse(data);
            if (data.error) {
                swal.fire({
                    title: 'Error',
                    text: data.error,
                    icon: 'error'
                });
            } else {
                // Completar campos automáticamente
                $("#cat_nom").val(data.CAT_NOM);
                $("#prod_pcompra").val(data.PROD_PCOMPRA);
                $("#prod_stock").val(data.PROD_STOCK);
                $("#und_nom").val(data.UND_NOM);
                $("#cat_id").val(data.CAT_ID);
            }
        });
    });

    $("#prov_id").change(function(){
        $("#prov_id").each(function(){
            prov_id= $(this).val();
            $.post("../../controller/proveedor.php?op=mostrar", {prov_id: prov_id}, function(data) {
                data=JSON.parse(data);
                $("#prov_ruc").val(data.PROV_RUC);
                $("#prov_direcc").val(data.PROV_DIRECC);
                $("#prov_telf").val(data.PROV_TELF);
                $("#prov_correo").val(data.PROV_CORREO);

            });
        });
    });

    $("#cat_id").change(function(){
        $("#cat_id").each(function(){
            cat_id= $(this).val();
            $.post("../../controller/producto.php?op=combo", {cat_id: cat_id}, function(data) {
                
                $("#prod_id").html(data);
            });
        });
    });

    $("#prod_id").change(function(){
        $("#prod_id").each(function(){
            prod_id= $(this).val();
            $.post("../../controller/producto.php?op=mostrar", {prod_id: prod_id}, function(data) {
                
                data=JSON.parse(data);
                $("#prod_pcompra").val(data.PROD_PCOMPRA);
                $("#prod_pcompra").attr("data-original", data.PROD_PCOMPRA); // Guardar el valor original
                $("#prod_stock").val(data.PROD_STOCK);
                $("#und_nom").val(data.UND_NOM);
            });
        });
    });
    
    
    

});




function mostrarDiv() {
    let div = document.getElementById("tipoPagoDiv");
    div.hidden = !div.hidden; // Alterna entre oculto y visible
}

$(document).on("click","#btnagregar", function(){
    var compr_id = $("#compr_id").val();
    var prod_id = $("#prod_id").val();
    var prod_pcompra_bd = parseFloat($("#prod_pcompra").attr("data-original"));
    var prod_pcompra = parseFloat($("#prod_pcompra").val()); // Precio ingresado manualmente
    var detc_cant = $("#detc_cant").val();

    console.log(prod_pcompra_bd);
    console.log(prod_pcompra);

    if($("#prod_id").val()=='' || $("#prod_pcompra").val()=='' || $("#detc_cant").val()==''){

        swal.fire({
            title: 'Compra',
            text: 'Error. Campos vacíos',
            icon: 'error'
        })

    }else if (prod_pcompra > prod_pcompra_bd) {
       // Llamamos a la función para actualizar los valores en el modal
       actualizarPreciosModal(prod_pcompra);

       // Abrimos el modal
       $("#modalmantenimiento").modal("show");

       // Configuramos el formulario para guardar los precios
       $("#mantenimiento_form").off("submit").on("submit", function (e) {
           e.preventDefault();
           guardarDetalle(compr_id, prod_id, prod_pcompra, detc_cant);
           $("#modalmantenimiento").modal("hide");
       });

    }else {
        // Si el precio es menor o igual, guardar directamente
        guardarDetalle(compr_id, prod_id, prod_pcompra, detc_cant);
    }

    

});

function guardarDetalle(compr_id, prod_id, prod_pcompra, detc_cant) {
    $.post("../../controller/compra.php?op=guardardetalle", {
        compr_id: compr_id,
        prod_id: prod_id,
        prod_pcompra: prod_pcompra,
        detc_cant: detc_cant
    }, function (data) {
        console.log(data);
    });

    $.post("../../controller/compra.php?op=calculo", { compr_id: compr_id }, function (data) {
        console.log(data);
        data = JSON.parse(data);
        $("#txtsubtotal").html(data.COMPR_SUBTOTAL);
        $("#txtigv").html(data.COMPR_IGV);
        $("#txttotal").html(data.COMPR_TOTAL);
    });

    $("#prod_pcompra").val('');
    $("#detc_cant").val('');
    listar(compr_id);
}

function actualizarPreciosModal(precioCompra) {
    console.log("Enviando al modal, precio de compra:", precioCompra);

    // Asignar el precio de compra al input del modal
    $("#prod_pcompram").val(precioCompra);

    // Calcular los precios iniciales con los aumentos predeterminados
    $(".porcentaje-input").each(function () {
        let porcentaje = parseFloat($(this).val()) || 0;
        let nuevoPrecio = (precioCompra * (1 + porcentaje / 100)).toFixed(0);
        $(this).closest("tr").find(".precio-input").val(nuevoPrecio);
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


function eliminar(detc_id, compr_id){
    swal.fire({
        title:"ELIMINAR",
        text:"¿Desea eliminar el registro?",
        icon: "question",
        confirmButtonText: "Si",
        showCancelButton: true,
        cancelButtonText: "No",
    }).then((result)=>{
        if (result.value){
            $.post("../../controller/compra.php?op=eliminardetalle",{detc_id:detc_id}, function(data)  {
                console.log(data);
            });
            

            $.post("../../controller/compra.php?op=calculo",{compr_id:compr_id}, function(data)  {
                console.log(data);
                data=JSON.parse(data);
                $("#txtsubtotal").html(data.COMPR_SUBTOTAL);
                $("#txtigv").html(data.COMPR_IGV);
                $("#txttotal").html(data.COMPR_TOTAL);
        
            });

            listar(compr_id);

            swal.fire({
                title:'Compra',
                text: 'Registro eliminado',
                icon: 'success',
                customClass: {
                    confirmButton: 'btn-danger'  // Personalización del botón de confirmación para el error
                }
            })
        }
    });
}

function listar(compr_id){
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
            url:"../../controller/compra.php?op=listardetalle",
            type:"post",
            data:{compr_id:compr_id}
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
}

document.addEventListener("DOMContentLoaded", function() {
    verificarCajaAbierta();
});

function verificarCajaAbierta() {
    var suc_id = $('#SUC_IDx').val();  // Obtener el valor dinámicamente del campo de sucursal
    fetch('../../controller/compra.php?op=verificarcaja', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded'
        },
        body: `accion=verificarcaja&suc_id=${suc_id}`  // Enviar el suc_id obtenido dinámicamente
    })
    .then(response => response.json())
    .then(data => {
        if (data.status === 'success') {
            swal.fire({
                title: 'Atención',
                text: data.message,
                icon: 'info',
                confirmButtonText: 'Ok',
                customClass: {
                    confirmButton: 'btn-info'  
                },
                buttonsStyling: false,
                confirmButtonClass: 'btn btn-info',
                allowOutsideClick: false,  // Bloquea el cierre fuera del modal
                allowEscapeKey: false  // Bloquea el cierre con la tecla Escape
            }).then(function(result) {
                if (result.isConfirmed) {
                    // Redirigir a otra sección
                    window.location.href = '../MntCaja/index.php';
                }
            });
        } else {
            swal.fire({
                title: 'Error',
                text: data.message,
                icon: 'error',
                confirmButtonText: 'Ok',
                customClass: {
                    confirmButton: 'btn-danger'
                },
                buttonsStyling: false,
                confirmButtonClass: 'btn btn-danger',
                allowOutsideClick: false,  // Bloquea el cierre fuera del modal
                allowEscapeKey: false  // Bloquea el cierre con la tecla Escape
            }).then(function(result) {
                if (result.isConfirmed) {
                    // Bloquear cualquier acción adicional
                    event.preventDefault();  // Evita cualquier acción de redirección
                    return false;  // Detiene el flujo
                }
            });
        }
    })
    .catch(error => {
        console.error('Error:', error);
        return false;  // Detiene la ejecución en caso de error
    });
}


$(document).on("click", "#btnguardar", function(){
    var compr_id = $("#compr_id").val();
    var doc_id = $("#doc_id").val();
    var pag_id = $("#pag_id").val();
    var prov_id = $("#prov_id").val();
    var prov_ruc = $("#prov_ruc").val();
    var prov_direcc = $("#prov_direcc").val();
    var prov_correo = $("#prov_correo").val();
    var compr_coment = $("#compr_coment").val();
    var mon_id = $("#mon_id").val();
    var nro_fact = $("#nro_fact").val(); 
    var fech_fact = $("#fech_fact").val(); 
    var caj_id = $("#caj_id").val(); 
    var caj_egr = $("#caj_egr").val(); 
    

    

    console.log($("#caj_id").val());
    // Verificación de campos vacíos
    if (nro_fact.trim() === '' || fech_fact.trim() === '' || doc_id === '0' || pag_id === '0' || prov_id === '0' || mon_id === '0') {
        swal.fire({
            title: 'Compra',
            text: 'Error. Campos vacíos',
            icon: 'error'
        })
    } else {
        $.post("../../controller/compra.php?op=calculo", {compr_id: compr_id}, function(data) {
            data = JSON.parse(data);
            if (data.COMPR_TOTAL == null) {
                swal.fire({
                    title: 'Compra',
                    text: 'Error. No existe detalle',
                    icon: 'error'
                })
            } else {
                $.post("../../controller/compra.php?op=guardar", {
                    compr_id: compr_id,
                    pag_id: pag_id,
                    prov_id: prov_id,
                    prov_ruc: prov_ruc,
                    prov_direcc: prov_direcc,
                    prov_correo: prov_correo,
                    compr_coment: compr_coment,
                    mon_id: mon_id,
                    doc_id: doc_id,
                    nro_fact: nro_fact, 
                    fech_fact: fech_fact, 
                    caj_id: caj_id
                }, function(data) {
                    swal.fire({
                        title: 'Compra',
                        text: 'Registrado correctamente con Nro: C-' + compr_id,
                        icon: 'success',
                        footer: "<a href='../ViewCompra/?c=" + compr_id + "' target='_blank'>Desea ver el documento</a>"
                    })
                    $.post("../../controller/caja.php?op=editaregr", {
                        caj_id: caj_id,
                        caj_egr: caj_egr
                        
                    }, function(data){
                        console.log(caj_id,caj_egr);
                        console.log("Correcto");
                    });
                });
            }
        });
    }
});


$(document).on("click","#btnlimpiar", function(){
    location.reload();
});

