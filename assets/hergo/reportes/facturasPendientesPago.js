$(document).ready(function() {
    tituloReporte()
    $('#clientes_filtro').select2({
        placeholder: 'Seleccione',
        width: 'resolve',
        allowClear: true
    });

});
$(document).on("change", "#almacen_filtro", function () {
    tituloReporte()
    retornarFacturasPendientes();
}) //para cambio filtro segun cada uno
$(document).on("click", "#pendientes", function () {
    tituloReporte();
    retornarFacturasPendientes();
})

function tituloReporte() {
    nomCliente = $('#clientes_filtro').find(':selected').text();
    almText = $('#almacen_filtro').find(":selected").text();
    if (nomCliente == '') {
        $('#nombreCliente').text('TODOS')
    } else {
        $('#nombreCliente').text(nomCliente)
    }
     
    $('#tituloReporte').text(almText);
}

function retornarFacturasPendientes()
{
    cliente = $("#clientes_filtro").val();
    almacen = $("#almacen_filtro").val();
    agregarcargando();
    $.ajax({
        type: "POST",
        url: base_url('index.php/Reportes/mostrarFacturasPendientesPago'),
        dataType: "json",
        data: {
            cliente : cliente,
            almacen : almacen
        }, 
    }).done(function (res) {
        quitarcargando();
        console.log(res);
        console.log(`Almacen: ${almacen} Cliente: ${cliente}`);
        datosselect = restornardatosSelect(res);
        $("#tablaFacturasPendientes").bootstrapTable('destroy');
        $("#tablaFacturasPendientes").bootstrapTable({ 
            data: res,
            striped: true,
            searchOnEnterKey: true,
            showColumns: true,
            filter: true,
            stickyHeader: true,
            stickyHeaderOffsetY: '50px',
            showFooter: true,
            footerStyle: footerStyle,
            columns: 
            [
                {
                    field: 'almacen',
                    title: 'Almacen',
                    width:'50px',
                    sortable: true,
                    visible: false
                },
                {
                    field: 'lote',
                    title: 'Lote',
                    width:'50px',
                    sortable: true,
                    align: 'center'
                },
                {
                    field: 'nFac',
                    title: 'N° Factura',
                    sortable: true,
                    width:'80px',
                    align: 'center',
                },
                {
                    field: 'fecha',
                    title: 'Fecha',
                    width:'100px',
                    sortable: true,
                    align: 'center',
                    formatter: formato_fecha_corta
                },
                {
                    field: 'nombreCliente',
                    title: 'Cliente',
                    sortable: true,
                     //visible:false,
                    filter: {
                        type: "select",
                        data: datosselect[0]
                    }
                },
                {
                    field: 'glosa',
                    title: 'Glosa',
                    sortable: true,
                },
                {
                    field: 'totalFactura',
                    title: 'Crédito',
                    sortable: true,
                    align: 'right',
                    width:'100px',
                    formatter: operateFormatter3,
                    footerFormatter: sumaColumna
                },
                {
                    field: 'totalPago',
                    title: 'Abono',
                    sortable: true,
                    align: 'right',
                    width:'100px',
                    formatter: operateFormatter3,
                    footerFormatter: sumaColumna
                },
                {
                    field: 'saldo',
                    title: 'Saldo',
                    sortable: true,
                    align: 'right',
                    width:'100px',
                    formatter: operateFormatter3,
                    footerFormatter: sumaColumna
                },
                
            ]
        });
    }).fail(function (jqxhr, textStatus, error) {
        var err = textStatus + ", " + error;
        console.log("Request Failed: " + err);
    });
}
function operateFormatter3(value, row, index) {
    num = Math.round(value * 100) / 100
    num = num.toFixed(2);
    return (formatNumber.new(num));
}
function footerStyle(value, row, index) {
    return {
        css: {
            "font-weight": "bold",
            "border-top": "3px solid white",
            "border-bottom": "3px solid white",
            "text-align": "right",
            "padding": "15px",
            "background-color": "#3c8dbc",
            "color": "white"
        }
    };
}
function sumaColumna(data) {
    field = this.field;
    let totalSum = data.reduce(function (sum, row) {
        return sum + (+row[field]);
    }, 0);
    return (formatNumber.new(totalSum.toFixed(2)));
}
function restornardatosSelect(res) {

    var cliente = new Array()
    var datos = new Array()
    $.each(res, function (index, value) {

        cliente.push(value.nombreCliente)
    })

    //alm.sort();
    cliente.sort();
    //responsable.sort();
    //console.log(nPago);
    //datos.push(alm.unique());
    datos.push(cliente.unique());
    //datos.push(responsable.unique());
    return (datos);
}
Array.prototype.unique = function (a) {
    return function () {
        return this.filter(a)
    }
}(function (a, b, c) {
    return c.indexOf(a, b + 1) < 0
});