console.log('object');
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
        /*$("#tablaKardex").bootstrapTable('destroy');    
        $("#tablaKardex").bootstrapTable({ ////********cambiar nombre tabla viata
            data: res,
            striped: true,
            //pagination: true,
            //pageSize: "100",
            //search: true,
            //showColumns: true,
            filter: true,
            stickyHeader: true,
            stickyHeaderOffsetY: '50px',
            showFooter: true,
            footerStyle: footerStyle,
            columns: [
                {
                    field: 'almacen',
                    title: 'Almacen',
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
                    field: 'tipo',
                    title: 'Tipo',
                    align: 'center'
                },
                {
                    field: 'numMov',
                    title: 'N° Mov',
                    align: 'center'
                },
                {
                    field: 'nombreproveedor',
                    title: 'Cliente | Proveedor',
                    align: 'center'
                },
                
                {
                    field: 'punitario',
                    title: 'P/U',
                    align: 'right',
                    formatter: operateFormatter3,
                },
                
                {
                    field: 'cantidad',
                    title: 'Cantidad',
                    align: 'right',
                    visible: false,
                    formatter: operateFormatter3,
                    //footerFormatter: sumaColumna
                },
                {
                    field: 'cantidad',
                    title: 'Ingresos',
                    align: 'right',
                    formatter: ingresos,
                    footerFormatter: sumaIngresos
                },
                {
                    field: 'cantidad',
                    title: 'Factura',
                    align: 'right',
                    formatter: factura,
                    footerFormatter: sumaFactura

                },
                {
                    field: 'cantidad',
                    title: 'N.E.',
                    align: 'right',
                    formatter: notaEntrega,
                   footerFormatter: sumaNE
                },
                {
                    field: 'cantidad',
                    title: 'Traspaso',
                    align: 'right',
                    formatter: traspaso,
                    footerFormatter: sumaOtros
                },
                {
                    field: '_cantidad',
                    title: 'Saldo',
                    align: 'right',
                    //visible: false,
                    formatter: operateFormatter3,
                    //footerFormatter: saldo
                },
                {
                    field: '_total',
                    title: 'Total',
                    align: 'right',
                    formatter: operateFormatter3,
                    //footerFormatter: total
                },
                
                {
                    field: '_cpp',
                    title: 'CPP',
                    align: 'right',
                    formatter: costoPromedio4,
                    //footerFormatter: cpp
                },
            ]
          });*/
    }).fail(function (jqxhr, textStatus, error) {
        var err = textStatus + ", " + error;
        console.log("Request Failed: " + err);
    });
}
/*function operateFormatter3(value, row, index) {
    num = Math.round(value * 100) / 100
    num = num.toFixed(2);
    return (formatNumber.new(num));
}
function costoPromedio4(value, row, index) {
    num = Number(value)
    num = num.toFixed(4);
    return (formatNumber.new(num));
}*/
function tituloReporte() {
    almText = $('#almacen_filtro').find(":selected").text();
    nomCliente = $('#clientes_filtro').find(':selected').text();
    $('#tituloReporte').text(almText);
    $('#nombreCliente').text(nomCliente);
}
/*function ingresos(value, row, index) {
    $ret = ''
    let suma =[]
    if (row.tipo=='II'||row.tipo=='IT'||row.tipo=='CL'||row.tipo=='ID'||row.tipo=='IMP') {
        $ret = row.cantidad
        $ret = Math.round( $ret * 100) / 100
        $ret = $ret.toFixed(2);
    } 
   // return ($ret);
    return (formatNumber.new($ret));
}

function factura(value, row, index) {
    $ret = ''
    if (row.tipo=='FAC') {
        $ret = Number(row.cantidad)
        $ret = Math.round( $ret * 100) / 100
        $ret = $ret.toFixed(2);
    } 
    return (formatNumber.new($ret));
}
function notaEntrega(value, row, index) {
    $ret = ''
    if (row.tipo=='NE') {
        $ret = Number(row.cantidad)
        $ret = Math.round( $ret * 100) / 100
        $ret = $ret.toFixed(2);
    } 
    return (formatNumber.new($ret));
}
function traspaso(value, row, index) {
    $ret = ''
    if (row.tipo=='VC'||row.tipo=='ET'||row.tipo=='EB') {
        $ret = Number(row.cantidad)
        $ret = Math.round( $ret * 100) / 100
        $ret = $ret.toFixed(2);
    } 
    return (formatNumber.new($ret));
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
function sumaFactura(data) {
    let facturas = data.filter(dato=>dato.tipo=='FAC')
    field = this.field;
    let totalSum = facturas.reduce(function (sum, row) {
        return sum + (+row[field]);
    }, 0);
    return (formatNumber.new(totalSum.toFixed(2)));
}

function sumaNE(data) {
    let notasEnt = data.filter(dato=>dato.tipo=='NE')
    field = this.field;
    let totalSum = notasEnt.reduce(function (sum, row) {
        return sum + (+row[field]);
    }, 0);
    return (formatNumber.new(totalSum.toFixed(2)));
}

function sumaIngresos(data) {
    let ingresos = data.filter(dato=>dato.tipo=='II'||dato.tipo=='IT'||dato.tipo=='CL'||dato.tipo=='ID'||dato.tipo=='IMP')
    field = this.field;
    let totalSum = ingresos.reduce(function (sum, row) {
        return sum + (+row[field]);
    }, 0);
    return (formatNumber.new(totalSum.toFixed(2)));
}
function sumaOtros(data) {
    let otros = data.filter(dato=>dato.tipo=='ET'||dato.tipo=='EB')
    field = this.field;
    let totalSum = otros.reduce(function (sum, row) {
        return sum + (+row[field]);
    }, 0);
    return (formatNumber.new(totalSum.toFixed(2)));
}
*/
