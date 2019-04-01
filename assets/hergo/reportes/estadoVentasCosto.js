
$(document).ready(function(){
    tituloReporte() 
    retornarestadoVentasCosto();
}) 
$(document).on("change", "#almacen_filtro", function () {
    tituloReporte() 
    retornarestadoVentasCosto();
})
$(document).on("change", "#moneda", function () {
    tituloReporte() 
    retornarestadoVentasCosto();
})
$(document).on("click", "#refresh", function () {
    tituloReporte() 
    retornarestadoVentasCosto();
})
$(document).on("click", "#excel", function () {
    let alm = $("#almacen_filtro").val()
    let mon = $("#moneda").val()
    let tc = (mon == 1) ?  glob_tipoCambio : 'BOB'
    alm = (alm == '') ?  'NN' : alm
    let excel = base_url("ReportesExcel/estadoVentasCostoItem/"+alm+"/"+tc);
    console.log(excel);
    location.href = (excel);
})

function retornarestadoVentasCosto() 
{   
    let mon = $("#moneda").val()
    alm = $("#almacen_filtro").val();
    agregarcargando();
    $.ajax({
        type:"POST",
        url: base_url('index.php/Reportes/mostrarEstadoVentasCosto'),
        dataType: "json",
        data: {
            alm: alm,
        },
    }).done(function(res){
        res.forEach(e => {
            if (e.codigo == null) {
                e.cpp = null
                e.precioVenta = null
                e.saldoCantidad = null 
                e.cantVendida = null
            }
        })
        console.log(res);
        quitarcargando();
        datosselect = restornardatosSelect(res)
        $("#estadoVentasCostos").bootstrapTable('destroy');
        $("#estadoVentasCostos").bootstrapTable({       
                data:res,    
                    striped:true,
                    search:true,
                    filter:true,
                    showColumns: true,
                    stickyHeader: true,
                    stickyHeaderOffsetY: '50px',
                    footerStyle: footerStyle,
                    rowStyle:rowStyle,
                columns:
                [
                    {
                        field: 'siglaLinea',
                        title: 'Sigla',
                        align: 'center',
                        visible: true,
                    },
                    {
                        field: 'linea',
                        title: 'Linea',
                        align: 'center',
                        visible: true,
                        filter: {
                            type: "select",
                            data: datosselect[0]
                        },
                    },
                    {
                        field: 'codigo',
                        title: 'Código',
                        align: 'center',
                        visible: true,
                    },
                    {
                        field: 'descrip',
                        title: 'Descripción',
                        align: 'left',
                        visible: true,
                        formatter: subTotal
                    },
                    {
                        field: 'uni',
                        title: 'Uni.',
                        align: 'center',
                        visible: true,
                        searchable: false,
                        formatter: totalVacio
                    },
                    {
                        field: 'cpp',
                        title: 'C/U BOB.',
                        align: 'right',
                        width:'80px',
                        visible: mon==1 ? false  : true,
                        searchable: false,
                        formatter: formatoDecimal,
                    },
                    {
                        field: 'cpp',
                        title: 'C/U $U$.',
                        align: 'right',
                        width:'80px',
                        visible: mon==1 ? true  : false,
                        searchable: false,
                        formatter: formatoDecimalDolares,
                    },
                    {
                        field: 'precioVenta',
                        title: 'P.P. Venta',
                        align: 'right',
                        width:'80px',
                        visible: mon==1 ? false  : true,
                        searchable: false,
                        formatter: formatoDecimal,
                    },
                    {
                        field: 'precioVenta',
                        title: 'P.P. Venta',
                        align: 'right',
                        width:'80px',
                        visible: mon==1 ? true  : false,
                        searchable: false,
                        formatter: formatoDecimalDolares,
                        //formatter: totalVacio
                    },
                    {
                        field: 'saldoCantidad',
                        title: 'Saldo',
                        align: 'right',
                        width:'80px',
                        visible: true,
                        searchable: false,
                        formatter: formatoDecimal,
                        //formatter: totalVacio
                    },
                    {
                        field: 'saldoValorado',
                        title: 'Saldo Valorado',
                        align: 'right',
                        width:'80px',
                        visible: mon==1 ? false  : true,
                        searchable: false,
                        formatter: formatoDecimal,
                    },
                    {
                        field: 'saldoValorado',
                        title: 'Saldo Valorado',
                        align: 'right',
                        width:'80px',
                        visible: mon==1 ? true  : false,
                        searchable: false,
                        formatter: formatoDecimalDolares,
                        //footerFormatter: sumaColumna
                    },
                    {
                        field: 'cantVendida',
                        title: 'Cant. Vendida',
                        align: 'right',
                        width:'80px',
                        visible: true,
                        searchable: false,
                        formatter: formatoDecimal,
                        //formatter: totalVacio
                    },
                    {
                        field: 'totalCosto',
                        title: 'Total Costo',
                        align: 'right',
                        width:'80px',
                        visible: mon==1 ? false  : true,
                        searchable: false,
                        formatter: formatoDecimal,
                        //footerFormatter: sumaColumna
                    },
                    {
                        field: 'totalCosto',
                        title: 'Total Costo',
                        align: 'right',
                        width:'80px',
                        visible: mon==1 ? true  : false,
                        searchable: false,
                        formatter: formatoDecimalDolares,
                        //footerFormatter: sumaColumna
                    },
                    {
                        field: 'totalVenta',
                        title: 'Total Ventas',
                        align: 'right',
                        width:'80px',
                        visible: mon==1 ? false  : true,
                        searchable: false,
                        formatter: formatoDecimal,
                        //footerFormatter: sumaColumna
                    },
                    {
                        field: 'totalVenta',
                        title: 'Total Ventas',
                        align: 'right',
                        width:'80px',
                        visible: mon==1 ? true  : false,
                        searchable: false,
                        formatter: formatoDecimalDolares,
                        //footerFormatter: sumaColumna
                    },
                    {
                        field: 'utilidad',
                        title: 'Utilidad',
                        align: 'right',
                        width:'80px',
                        visible: mon==1 ? false  : true,
                        searchable: false,
                        formatter: formatoDecimal,
                        //footerFormatter: sumaColumna
                    },
                    {
                        field: 'utilidad',
                        title: 'Utilidad',
                        align: 'right',
                        width:'80px',
                        visible: mon==1 ? true  : false,
                        searchable: false,
                        formatter: formatoDecimalDolares,
                        //footerFormatter: sumaColumna
                    },

                ]
            });
    }).fail(function( jqxhr, textStatus, error ) {
    var err = textStatus + ", " + error;
    console.log( "Request Failed: " + err );
    });
 }
 function formatoDecimal(value, row, index) {
     if (value == null) {
         return ''
     } else {
         num=Math.round(value * 100) / 100
         num=num.toFixed(2);
         return (formatNumber.new(num));
     }
}
function formatoDecimalDolares(value, row, index) {
    if (value == null) {
        return ''
    } else {
        num=Math.round(value * 100) / 100
        num = num / glob_tipoCambio
        num=num.toFixed(2);
        return (formatNumber.new(num));
    }
}
function totalVacio(value, row, index) {
    if (row.codigo == null) {
        value = ''
    }
    return (value);
}
function subTotal(value, row, index) {
    if (row.codigo == null && row.siglaLinea == null) {
        value = 'TOTAL GENERAL'
    }
    else if (row.codigo == null) {
        value = 'TOTAL ' + row.linea
    }
    return (value);
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
function rowStyle(row, index) {
    if (row.codigo==null) {
        return {
            css: {
                //"font-weight": "bold",
                //"border-top": "3px solid white",
                //"border-bottom": "3px solid white",
                "text-align": "right",
                //"padding": "15px",
                "background-color": "#3c8dbc",
                "color": "white",
               // "font-size":"120%",
            }
        };
    }
    return {};
}
function tituloReporte() {
    almText = $('#almacen_filtro').find(":selected").text();
    moneda = ($('#moneda').val() == 1) ? 'DOLARES' : 'BOLIVIANOS'
    
    $('#tituloReporte').text(almText);
    $('#monedaTitulo').text(moneda);
}
function restornardatosSelect(res) {


    let linea = new Array()
    var datos = new Array()
    $.each(res, function (index, value) {
        linea.push(value.linea)
    })

    linea.sort();
    datos.push(linea.unique());
    return (datos);
}
Array.prototype.unique = function (a) {
    return function () {
        return this.filter(a)
    }
}(function (a, b, c) {
    return c.indexOf(a, b + 1) < 0
});
