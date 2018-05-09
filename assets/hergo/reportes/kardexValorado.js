$(document).ready(function() {
    $('#articulos_filtro').select2({
        placeholder: 'Seleccione',
        allowClear: true
    });
});
$(document).on("click", "#kardex", function () {
    retornarKardex();
})
function retornarKardex() {
    var alm = $("#almacen_filtro").val()
    var art = $("#articulos_filtro").val()
    $.ajax({
        type: "POST",
        url: base_url('index.php/Reportes/mostrarKardexIndividual'),
        dataType: "json",
        data: {
            a: alm,
            art: art
        },
        
    }).done(function (res) {
        console.log(alm + " " + art)
        $("#tablaKardex").bootstrapTable('destroy');    
        $("#tablaKardex").bootstrapTable({ ////********cambiar nombre tabla viata
            data: res,
            striped: true,
            //pagination: true,
            //pageSize: "100",
            //search: true,
            //showColumns: true,
            filter: true,
            //stickyHeader: true,
            //stickyHeaderOffsetY: '50px',
            //showFooter: true,
            //footerStyle: footerStyle,
            columns: [
                {
                    field: 'almacen',
                    title: 'Almacen',
                    sortable: true,
                    align: 'center'
                },
                {
                    field: 'fecha',
                    title: 'Fecha',
                    sortable: true,
                    align: 'center'
                },
                {
                    field: 'operacion',
                    title: '',
                    sortable: true,
                    align: 'center'
                },
                {
                    field: 'tipo',
                    title: 'Tipo',
                    sortable: true,
                    align: 'center'
                },
                {
                    field: 'numMov',
                    title: 'N° Movimiento',
                    sortable: true,
                    align: 'center'
                },
                {
                    field: 'nombreproveedor',
                    title: 'Cliente | Proveedor',
                    sortable: true,
                    align: 'center'
                },
                {
                    field: 'cantidad',
                    title: 'Cantidad',
                    sortable: true,
                    align: 'center'
                },
                {
                    field: 'punitario',
                    title: 'P/U',
                    sortable: true,
                    align: 'center'
                },
                
                
                
                {
                    field: '_cantidad',
                    title: 'Cantidad',
                    sortable: true,
                    align: 'center'
                },
                {
                    field: '_cpp',
                    title: 'CPP',
                    sortable: true,
                    align: 'center'
                },
                {
                    field: '_total',
                    title: 'Total',
                    sortable: true,
                    align: 'center'
                },
                {
                    field: '_totalAux',
                    title: 'Aux',
                    sortable: true,
                    align: 'center'
                }


            ]
          });
    }).fail(function (jqxhr, textStatus, error) {
        var err = textStatus + ", " + error;
        console.log("Request Failed: " + err);
    });
}



function tituloReporte() {
    almText = $('#almacen_filtro').find(":selected").text();
    tipoText = $('#tipo_filtro').find(':selected').text();
    $('#tituloAlmacen').text(almText);
    $('#tituloTipo').text(tipoText);
    $('#ragoFecha').text("DEL " + iniciofecha.format('DD/MM/YYYY') + "  AL  " + finfecha.format('DD/MM/YYYY'));

}