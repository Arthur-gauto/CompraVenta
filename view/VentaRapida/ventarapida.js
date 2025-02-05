var suc_id = $('#SUC_IDx').val();
var usu_id = $('#USU_IDx').val();

$(document).ready(function(){
    // Inicializar búsqueda de productos
    $("#buscar_producto").autocomplete({
        source: function(request, response) {
            $.ajax({
                url: "../../controller/producto.php?op=buscar",
                type: "POST",
                dataType: "json",
                data: { 
                    term: request.term,
                    suc_id: suc_id
                },
                success: function(data) {
                    // Formatear los datos para el autocomplete
                    var formattedData = data.map(function(item) {
                        return {
                            label: item.PROD_NOM + ' - ' + item.CAT_NOM + ' (' + item.UND_NOM + ')',
                            value: item.PROD_NOM,
                            id: item.PROD_ID,
                            nombre: item.PROD_NOM,
                            precio: item.PROD_PVENTA,
                            stock: item.PROD_STOCK
                        };
                    });
                    response(formattedData);
                },
                error: function(xhr, status, error) {
                    console.log("Error en la búsqueda:", error);
                    console.log("Respuesta del servidor:", xhr.responseText);
                }
            });
        },
        minLength: 2,
        select: function(event, ui) {
            if (ui.item) {
                agregarProducto({
                    PROD_ID: ui.item.id,
                    PROD_NOM: ui.item.nombre,
                    PROD_PVENTA: ui.item.precio,
                    PROD_STOCK: ui.item.stock
                });
                $(this).val('');
            }
            return false;
        }
    }).autocomplete("instance")._renderItem = function(ul, item) {
        return $("<li>")
            .append("<div>" + item.label + " - S/ " + item.precio + "</div>")
            .appendTo(ul);
    };

    // Eventos de cantidad
    $(document).on('change', '.cantidad', function() {
        let fila = $(this).closest('tr');
        let stock = parseInt(fila.data('stock'));
        let cantidad = parseInt($(this).val());

        if (cantidad > stock) {
            Swal.fire({
                icon: 'warning',
                title: 'Stock insuficiente',
                text: 'No hay suficiente stock disponible'
            });
            $(this).val(stock);
        } else if (cantidad < 1) {
            $(this).val(1);
        }
        actualizarSubtotal(fila);
    });

    // Evento eliminar producto
    $(document).on('click', '.eliminar', function() {
        $(this).closest('tr').remove();
        calcularTotal();
    });

    // Calcular vuelto al ingresar efectivo
    $("#efectivo").on("input", function() {
        calcularVuelto();
    });

    // Botón Cobrar
    $("#btnpagar").click(function() {
        realizarVenta();
    });

    // Botón Cancelar
    $("#btncancelar").click(function() {
        limpiarVenta();
    });
});

// Función para agregar producto
function agregarProducto(producto) {
    if (!producto || !producto.PROD_ID) {
        console.log("Producto inválido:", producto);
        return;
    }

    let tabla = $("#detalles_venta tbody");
    let existe = false;
    
    tabla.find('tr').each(function() {
        if ($(this).data('id') == producto.PROD_ID) {
            let cantidad = parseInt($(this).find('.cantidad').val()) + 1;
            if (cantidad <= producto.PROD_STOCK) {
                $(this).find('.cantidad').val(cantidad);
                actualizarSubtotal($(this));
                existe = true;
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Stock insuficiente',
                    text: 'No hay suficiente stock disponible'
                });
            }
            return false;
        }
    });

    if (!existe) {
        let fila = `
            <tr data-id="${producto.PROD_ID}" data-stock="${producto.PROD_STOCK}">
                <td>${producto.PROD_NOM}</td>
                <td>
                    <input type="number" class="form-control cantidad" 
                           value="1" min="1" max="${producto.PROD_STOCK}">
                </td>
                <td>S/ ${producto.PROD_PVENTA}</td>
                <td class="subtotal">S/ ${producto.PROD_PVENTA}</td>
                <td>
                    <button class="btn btn-danger btn-sm eliminar">
                        <i class="ri-delete-bin-line"></i>
                    </button>
                </td>
            </tr>
        `;
        tabla.append(fila);
        actualizarSubtotal(tabla.find('tr:last'));
    }

    calcularTotal();
}

function actualizarSubtotal(fila) {
    let cantidad = parseInt(fila.find('.cantidad').val());
    let precio = parseFloat(fila.find('td:eq(2)').text().replace('S/ ', ''));
    let subtotal = cantidad * precio;
    fila.find('.subtotal').text('S/ ' + subtotal.toFixed(2));
    calcularTotal();
}

function calcularTotal() {
    let total = 0;
    $('#detalles_venta tbody tr').each(function() {
        total += parseFloat($(this).find('.subtotal').text().replace('S/ ', ''));
    });
    $('#total').text('S/ ' + total.toFixed(2));
    calcularVuelto();
}

function calcularVuelto() {
    let total = parseFloat($('#total').text().replace('S/ ', ''));
    let efectivo = parseFloat($('#efectivo').val() || 0);
    let vuelto = efectivo - total;
    $('#vuelto').text('S/ ' + (vuelto >= 0 ? vuelto.toFixed(2) : '0.00'));
    
    // Habilitar/deshabilitar botón de cobro
    $('#btnpagar').prop('disabled', !(vuelto >= 0 && total > 0));
}

function limpiarVenta() {
    $("#detalles_venta tbody").empty();
    $("#efectivo").val('');
    $("#total").text('S/ 0.00');
    $("#vuelto").text('S/ 0.00');
    $("#buscar_producto").focus();
    $('#btnpagar').prop('disabled', true);
}

function realizarVenta() {
    let productos = [];
    let total = parseFloat($('#total').text().replace('S/ ', ''));
    let efectivo = parseFloat($('#efectivo').val());

    // Recopilar productos
    $('#detalles_venta tbody tr').each(function() {
        productos.push({
            prod_id: $(this).data('id'),
            cantidad: parseInt($(this).find('.cantidad').val()),
            precio: parseFloat($(this).find('td:eq(2)').text().replace('S/ ', ''))
        });
    });

    // Realizar la venta
    $.ajax({
        url: "../../controller/ventarapida.php?op=registrar_venta",
        type: "POST",
        data: {
            productos: JSON.stringify(productos),
            total: total,
            efectivo: efectivo,
            suc_id: suc_id,
            usu_id: usu_id
        },
        success: function(response) {
            let data = JSON.parse(response);
            if(data.status == "success") {
                Swal.fire({
                    icon: 'success',
                    title: '¡Venta Realizada!',
                    text: 'Venta #' + data.venta_id + ' registrada correctamente'
                }).then((result) => {
                    limpiarVenta();
                });
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: data.message || 'Error al procesar la venta'
                });
            }
        },
        error: function(xhr, status, error) {
            console.log("Error en la venta:", error);
            console.log("Respuesta del servidor:", xhr.responseText);
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Error al procesar la venta'
            });
        }
    });
}