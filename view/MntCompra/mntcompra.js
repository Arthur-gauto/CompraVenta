var emp_id = $('#EMP_IDx').val();
var suc_id = $('#SUC_IDx').val();
$(document).ready(function(){
    $('#prov_id').select2();

    $('#cat_id').select2();

    $('#prod_id').select2();

    $('#pag_id').select2();
    
    $.post("../../controller/proveedor.php?op=combo",{emp_id:emp_id},function(data){
        console.log(data);
        $("#prov_id").html(data);
    });

    $.post("../../controller/categoria.php?op=combo",{suc_id:suc_id},function(data){
        $("#cat_id").html(data);
    });

    $.post("../../controller/pago.php?op=combo",function(data){
        $("#pag_id").html(data);
    });

    $("#prov_id").change(function(){
        $("#prov_id").each(function(){
            prov_id = $(this).val();

            $.post("../../controller/proveedor.php?op=mostrar",{prov_id:prov_id},function(data){
                data=JSON.parse(data);
                $("#prov_ruc").val(data.PROV_RUC);
                $("#prov_direcc").val(data.PROV_DIRECC);
                $("#prov_correo").val(data.PROV_CORREO);
                $("#prov_telf").val(data.PROV_TELF);
            });
        });
    });

    $("#cat_id").change(function(){
        $("#cat_id").each(function(){
            cat_id = $(this).val();
            $.post("../../controller/producto.php?op=combo",{cat_id:cat_id},function(data){
                $("#prod_id").html(data);
            });
        });
    });
    $("#prod_id").change(function(){
        $("#prod_id").each(function(){
            prod_id = $(this).val();
            $.post("../../controller/producto.php?op=mostrar",{prod_id:prod_id},function(data){
                console.log(data);
                data=JSON.parse(data);
                $('#prod_pcompra').val(data.PROD_PCOMPRA);
                $('#prod_stock').val(data.PROD_STOCK);
                $('#und_nom').val(data.UND_NOM);
            });

        });
    });
});