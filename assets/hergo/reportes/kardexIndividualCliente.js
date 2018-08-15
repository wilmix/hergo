$(document).ready(function() {
    tituloReporte()
});


$(document).on("click", "#kardex", function () {
    tituloReporte();
    retornarKardexCliente();
    
})
$(document).on("click", "#refresh", function () {
    tituloReporte();
    retornarKardexCliente();
})
$(document).on("change", "#clientes_filtro", function () {
    tituloReporte();
    retornarKardexCliente();
})

function retornarKardexCliente() {
    let almacen = $("#almacen_filtro").val()
    let cliente = $("#clientes_filtro").val()
    agregarcargando();
    $.ajax({
        type: "POST",
        url: base_url('index.php/Reportes/mostrarKardexIndividualCliente'),
        dataType: "json",
        data: {
            almacen: almacen,
            cliente: cliente
        },
    }).done(function (res) {
        quitarcargando(); 
        console.log(almacen + " " + cliente)
        console.log(res);
        if (res[0].fecha ===null) {
            res.shift()
        }
        console.log(res);
        $("#tablaKardex").bootstrapTable('destroy');    
        $("#tablaKardex").bootstrapTable({ 
            data: res,
            striped: true,
            filter: true,
            stickyHeader: true,
            stickyHeaderOffsetY: '50px',
            showFooter: true,
            footerStyle: footerStyle,
            columns: [
                {
                    field: 'idCliente',
                    title: 'Almacen',
                    align: 'center',
                    visible: false
                },
                {
                    field: 'nombreCliente',
                    title: 'Cliente',
                    align: 'center',
                    visible: false
                },
                {
                    field: 'fecha',
                    title: 'Fecha',
                    align: 'center',
                    formatter: formato_fecha_corta

                },
                {
                    field: 'numDocumento',
                    title: 'N° Doc.',
                    align: 'left'
                },
                {
                    field: 'almacen',
                    title: 'Alm.',
                    align: 'center'
                },
                {
                    field: 'detalle',
                    title: 'Detalle',
                    align: 'center'
                },
        
                {
                    field: 'saldoNE',
                    title: 'NotaEntrega',
                    align: 'right',
                    formatter: operateFormatter3,
                    footerFormatter: sumaColumna
                },
                {
                    field: 'saldoTotalFactura',
                    title: 'Factura',
                    align: 'right',
                    formatter: operateFormatter3,
                    footerFormatter: sumaColumna
                },
                {
                    field: 'saldoTotalPago',
                    title: 'Pago',
                    align: 'right',
                    formatter: operateFormatter3,
                    footerFormatter: sumaColumna

                },
                {
                    field: '',
                    title: 'Total',
                    align: 'right',
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
function footerStyle(value, row, index) {
    return {
        css: {
            "font-weight": "normal",
            "border-top": "3px solid white",
            "border-bottom": "3px solid white",
            "text-align": "right",
            "padding": "15px",
            "background-color": "#3c8dbc",
            "color": "white"
        }
    };
}
function operateFormatter3(value, row, index) {
    num = Math.round(value * 100) / 100
    num = num.toFixed(2);
    return (formatNumber.new(num));
}
function tituloReporte() {
    almText = $('#almacen_filtro').find(":selected").text();
    nomCliente = $('#clientes_filtro').find(':selected').text();
    $('#tituloReporte').text(almText);
    $('#nombreCliente').text(nomCliente);
}
function sumaColumna(data) {
    field = this.field;
    let totalSum = data.reduce(function (sum, row) {
      return sum + (+row[field]);
    }, 0);
    return (formatNumber.new(totalSum.toFixed(2)));
  }
