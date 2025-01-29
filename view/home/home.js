var suc_id = $('#SUC_IDx').val();

$.post("../../controller/compra.php?op=listartopproducto",{suc_id:suc_id},function(data){
    console.log("Hello, World!");
    $("#listtopcompraproducto").html(data);
});

$.post("../../controller/venta.php?op=listartopproducto",{suc_id:suc_id},function(data){
    console.log("Hello, World!");
    $("#listtopventaproducto").html(data);
});