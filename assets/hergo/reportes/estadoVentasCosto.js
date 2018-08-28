
$(document).ready(function(){
    retornarestadoVentasCosto();
}) 
$(document).on("change", "#almacen_filtro", function () {
    retornarestadoVentasCosto();
})

function retornarestadoVentasCosto() //*******************************
{   
    alm = $("#almacen_filtro").val();
    agregarcargando();
    $.ajax({
        type:"POST",
        url: base_url('index.php/Reportes/mostrarEstadoVentasCosto'), //******controlador
        dataType: "json",
        data: {
            alm: alm
        },
    }).done(function(res){
        console.log(alm);
    	quitarcargando();
        $("#estadoVentasCostos").bootstrapTable('destroy');
        $("#estadoVentasCostos").bootstrapTable({       

                data:res,    
                    striped:true,
                    search:true,
                    filter:true,
                    strictSearch: true,
                    stickyHeader: true,
                    stickyHeaderOffsetY: '50px',
                    showFooter: true,
                    footerStyle: footerStyle,
                columns:
                [
                    {
                        field: 'Sigla',
                        title: 'Linea',
                        align: 'center',
                        visible: true
                    },
                    {
                        field: 'CodigoArticulo',
                        title: 'Código',
                        align: 'center',
                        visible: true
                    },
                    {
                        field: 'Descripcion',
                        title: 'Descripción',
                        align: 'left',
                        visible: true
                    },
                    {
                        field: 'Unidad',
                        title: 'Uni.',
                        align: 'center',
                        visible: true,
                        searchable: false,
                    },
                    {
                        field: 'costoPromedioPonderado',
                        title: 'Costo Uni.',
                        align: 'right',
                        width:'80px',
                        visible: true,
                        searchable: false,
                        formatter: formatoDecimal,
                    },
                    {
                        field: 'ppVenta',
                        title: 'P.P. Venta',
                        align: 'right',
                        width:'80px',
                        visible: true,
                        searchable: false,
                        formatter: formatoDecimal,
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
                        visible: true,
                        searchable: false,
                        formatter: formatoDecimal,
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
                        visible: true,
                        searchable: false,
                        formatter: formatoDecimal,
                        footerFormatter: sumaColumna
                    },
                    {
                        field: 'totalVentas',
                        title: 'Total Ventas',
                        align: 'right',
                        width:'80px',
                        visible: true,
                        searchable: false,
                        formatter: formatoDecimal,
                        footerFormatter: sumaColumna
                    },
                    {
                        field: 'utilidad',
                        title: 'Utilidad',
                        align: 'right',
                        width:'80px',
                        visible: true,
                        searchable: false,
                        formatter: formatoDecimal,
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