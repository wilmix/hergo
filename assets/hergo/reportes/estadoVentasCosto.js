
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
    alm = $("#almacen_filtro").val()
    let excel = base_url("ReportesExcel/estadoVentasCostoItem/"+alm);
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
            alm: alm
        },
    }).done(function(res){
        console.log(glob_tipoCambio);
    	quitarcargando();
        $("#estadoVentasCostos").bootstrapTable('destroy');
        $("#estadoVentasCostos").bootstrapTable({       

                data:res,    
                    striped:true,
                    search:true,
                    filter:true,
                    stickyHeader: true,
                    stickyHeaderOffsetY: '50px',
                    showFooter: true,
                    footerStyle: footerStyle,
                    rowStyle:rowStyle,
                columns:
                [
                    {
                        field: 'sigla',
                        title: 'Linea',
                        align: 'center',
                        visible: true
                    },
                    {
                        field: 'codigo',
                        title: 'Código',
                        align: 'center',
                        visible: true
                    },
                    {
                        field: 'descrip',
                        title: 'Descripción',
                        align: 'left',
                        visible: true
                    },
                    {
                        field: 'unidad',
                        title: 'Uni.',
                        align: 'center',
                        visible: true,
                        searchable: false,
                    },
                    {
                        field: 'costo',
                        title: 'C/U BOB.',
                        align: 'right',
                        width:'80px',
                        visible: mon==1 ? false  : true,
                        searchable: false,
                        formatter: formatoDecimal,
                    },
                    {
                        field: 'costo',
                        title: 'C/U $U$.',
                        align: 'right',
                        width:'80px',
                        visible: mon==1 ? true  : false,
                        searchable: false,
                        formatter: formatoDecimalDolares,
                    },
                    {
                        field: 'ppVenta',
                        title: 'P.P. Venta',
                        align: 'right',
                        width:'80px',
                        visible: mon==1 ? false  : true,
                        searchable: false,
                        formatter: formatoDecimal,
                    },
                    {
                        field: 'ppVenta',
                        title: 'P.P. Venta',
                        align: 'right',
                        width:'80px',
                        visible: mon==1 ? true  : false,
                        searchable: false,
                        formatter: formatoDecimalDolares,
                    },
                    {
                        field: 'saldo',
                        title: 'Saldo',
                        align: 'right',
                        width:'80px',
                        visible: true,
                        searchable: false,
                        formatter: formatoDecimal,
                    },
                    {
                        field: 'saldoValorado',
                        title: 'Saldo Valorado',
                        align: 'right',
                        width:'80px',
                        visible: mon==1 ? false  : true,
                        searchable: false,
                        formatter: formatoDecimal,
                        footerFormatter: sumaColumna
                    },
                    {
                        field: 'saldoValorado',
                        title: 'Saldo Valorado',
                        align: 'right',
                        width:'80px',
                        visible: mon==1 ? true  : false,
                        searchable: false,
                        formatter: formatoDecimalDolares,
                        footerFormatter: sumaColumna
                    },
                    {
                        field: 'cantidadVendida',
                        title: 'Cant. Vendida',
                        align: 'right',
                        width:'80px',
                        visible: true,
                        searchable: false,
                        formatter: formatoDecimal,
                    },
                    {
                        field: 'totalCosto',
                        title: 'Total Costo',
                        align: 'right',
                        width:'80px',
                        visible: mon==1 ? false  : true,
                        searchable: false,
                        formatter: formatoDecimal,
                        footerFormatter: sumaColumna
                    },
                    {
                        field: 'totalCosto',
                        title: 'Total Costo',
                        align: 'right',
                        width:'80px',
                        visible: mon==1 ? true  : false,
                        searchable: false,
                        formatter: formatoDecimalDolares,
                        footerFormatter: sumaColumna
                    },
                    {
                        field: 'totalVentas',
                        title: 'Total Ventas',
                        align: 'right',
                        width:'80px',
                        visible: mon==1 ? false  : true,
                        searchable: false,
                        formatter: formatoDecimal,
                        footerFormatter: sumaColumna
                    },
                    {
                        field: 'totalVentas',
                        title: 'Total Ventas',
                        align: 'right',
                        width:'80px',
                        visible: mon==1 ? true  : false,
                        searchable: false,
                        formatter: formatoDecimalDolares,
                        footerFormatter: sumaColumna
                    },
                    {
                        field: 'utilidad',
                        title: 'Utilidad',
                        align: 'right',
                        width:'80px',
                        visible: mon==1 ? false  : true,
                        searchable: false,
                        formatter: formatoDecimal,
                        footerFormatter: sumaColumna
                    },
                    {
                        field: 'utilidad',
                        title: 'Utilidad',
                        align: 'right',
                        width:'80px',
                        visible: mon==1 ? true  : false,
                        searchable: false,
                        formatter: formatoDecimalDolares,
                        footerFormatter: sumaColumna
                    },

                ]
            });
    }).fail(function( jqxhr, textStatus, error ) {
    var err = textStatus + ", " + error;
    console.log( "Request Failed: " + err );
    });
 }
 function formatoDecimal(value, row, index) {
    num=Math.round(value * 100) / 100
    num=num.toFixed(2);
    return (formatNumber.new(num));
}
 function formatoDecimalDolares(value, row, index) {
    num=Math.round(value * 100) / 100
    num = num / glob_tipoCambio
    num=num.toFixed(2);
    return (formatNumber.new(num));
}
function sumaColumna(data) {
    field = this.field;
    let totalSum = data.reduce(function (sum, row) {
      return sum + (+row[field]);
    }, 0);
    return (formatNumber.new(totalSum.toFixed(2)));
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
    if (row.descrip=='') {
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