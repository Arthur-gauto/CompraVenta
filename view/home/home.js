var suc_id = $('#SUC_IDx').val();

$.post("../../controller/compra.php?op=listartopproducto",{suc_id:suc_id},function(data){
    $("#listtopcompraproducto").html(data);
});

$.post("../../controller/venta.php?op=listartopproducto",{suc_id:suc_id},function(data){
    $("#listtopventaproducto").html(data);
});

$.post("../../controller/compra.php?op=top5",{suc_id:suc_id},function(data){
    $("#listventatop5").html(data);
});


$.post("../../controller/categoria.php?op=wombocombo",{suc_id:suc_id},function(data){
    $("#listcategoriastock").html(data);
});

$.post("../../controller/compra.php?op=compraventa",{suc_id:suc_id},function(data){
    $("#listcompraventa").html(data);
});

$.ajax({
    url:"../../controller/compra.php?op=dountcompra",
    method: "POST",
    data: {suc_id:suc_id},
    success: function(data){

        data=JSON.parse(data);

        var categoria =[];
        var cantidad = [];
        for (var i in data){
            categoria.push(data[i].CAT_NOM);
            cantidad.push(data[i].CANT);
        }

        var isdoughnutchart = document.getElementById("doughnut");
        (doughnutChartColors = getChartColorsArray("doughnut")),
        doughnutChartColors && (lineChart = new Chart(isdoughnutchart, {
            type: "doughnut",
            data: {
                labels: categoria,
                datasets: [
                {
                    data: cantidad,
                    backgroundColor: doughnutChartColors,
                    hoverBackgroundColor: doughnutChartColors,
                    hoverBorderColor: "#fff",
                },
                ],
            },
            options: {
                plugins: { legend: { labels: { font: { family: "Poppins" } } } },
            },
            }));
    }
})
