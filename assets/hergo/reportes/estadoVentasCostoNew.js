let iniciofecha = moment().subtract(0, 'year').startOf('year')
let finfecha = moment().subtract(0, 'year').endOf('year')
$(document).ready(function(){
    $('#export').click(function () {
        $('#estadoVentasCostosNew').tableExport({
        type:'excel',
        fileName: 'Estado de Ventas y Costos',
        numbers: {output : false}
        })
      });

        let start = moment().subtract(0, 'year').startOf('year')
        let end = moment().subtract(0, 'year').endOf('year')

        $(function () {
            moment.locale('es');

            function cb(start, end) {
            $('#fechapersonalizada span').html(start.format('D MMMM, YYYY') + ' - ' + end.format('D MMMM, YYYY'));
            iniciofecha = start
            finfecha = end
            }

            $('#fechapersonalizada').daterangepicker({

            locale: {
                format: 'DD/MM/YYYY',
                applyLabel: 'Aplicar',
                cancelLabel: 'Cancelar',
                customRangeLabel: 'Personalizado',
            },
            startDate: start,
            endDate: end,
            ranges: {
                'Mes Actual': [moment().subtract(0, 'month').startOf('month'), moment().subtract(0, 'month').endOf('month')],
                "Gestión Actual": [moment().subtract(0, 'year').startOf('year'), moment().subtract(0, 'year').endOf('year')],
                "Gestión Anterior": [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')],

            }
            }, cb);

            cb(start, end);

        });
        $('#fechapersonalizada').on('apply.daterangepicker', function (ev, picker) {
            tituloReporte() 
            retornarestadoVentasCosto();
        });
            tituloReporte() 
            retornarestadoVentasCosto();
}) 
$(document).on("change", "#almacen_filtro", function () {
    tituloReporte() 
    retornarestadoVentasCosto();
})
$(document).on("click", "#pdf", function () {
    let alm = $("#almacen_filtro").val()
    let imprimir = base_url("pdf/EstadoVentasCosto/index/") + alm;
    window.open(imprimir);
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
{   let ini =  iniciofecha.format('YYYY-MM-DD')
    let fin =  finfecha.format('YYYY-MM-DD')
    let mon = $("#moneda").val()
    let alm = $("#almacen_filtro").val();
    agregarcargando();
    $.ajax({
        type:"POST",
        url: base_url('index.php/Reportes/mostrarEstadoVentasCostoNew'),
        dataType: "json",
        data: {
            alm: alm,
            ini:ini,
            fin:fin,
            mon:mon
        },
    }).done(function(res){
        quitarcargando();
        datosselect = restornardatosSelect(res)
        $("#estadoVentasCostosNew").bootstrapTable('destroy');
        $("#estadoVentasCostosNew").bootstrapTable({       
                data:res,    
                    striped:true,
                    search:true,
                    filter:true,
                    showColumns: true,
                    stickyHeader: true,
                    stickyHeaderOffsetY: '50px',
                    footerStyle: footerStyle,
                    rowStyle:rowStyle,
                    pagination: false,
                columns:
                [
                    {
                        field: 'linea',
                        title: 'Marca',
                        align: 'center',
                        filter: {
                            type: "select",
                            data: datosselect[1]
                        },
                        //visible: false,
                    },
                    {
                        field: 'lineaS',
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
                        formatter:totalVacio
                    },
                    {
                        field: 'descp',
                        title: 'Descripción',
                        align: 'left',
                        visible: true,
                        formatter:subTotal
                    },
                    {
                        field: 'uni',
                        title: 'Uni.',
                        align: 'center',
                        visible: true,
                        searchable: false,
                        formatter:totalVacio
                    },
                    {
                        field: 'cpp',
                        title: 'C.P.P.',
                        align: 'right',
                        width:'80px',
                        searchable: false,
                        formatter: formatoDecimalVacio
                    },
                    {
                        field: '',
                        title: 'P.P. Venta',
                        align: 'right',
                        width:'80px',
                        searchable: false,
                        formatter: formatoPPVenta,
                    },
                    {
                        field: 'cantidadSaldo',
                        title: 'Saldo',
                        align: 'right',
                        width:'80px',
                        visible: true,
                        searchable: false,
                        formatter: formatoDecimalVacio,
                    },
                    {
                        field: 'invFinal',
                        title: 'Inv.Final',
                        align: 'right',
                        width:'80px',
                        searchable: false,
                        formatter: formatoDecimal,
                    },
                    {
                        field: 'fac',
                        title: 'Cant. Vendida',
                        align: 'right',
                        width:'80px',
                        visible: true,
                        searchable: false,
                        formatter: formatoDecimalVacio,
                    },
                    {
                        field: 'cmv',
                        title: 'C.M.V.',
                        align: 'right',
                        width:'80px',
                        searchable: false,
                        formatter: formatoDecimal,
                        //footerFormatter: sumaColumna
                    },

                    {
                        field: 'totalVentasAcum',
                        title: 'Ventas',
                        align: 'right',
                        width:'80px',
                        searchable: false,
                        formatter: formatoDecimal,
                        //footerFormatter: sumaColumna
                    },
                    {
                        field: 'utilidad',
                        title: 'Utilidad',
                        align: 'right',
                        width:'80px',
                        searchable: false,
                        formatter: formatoDecimal,
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
    
         num=Math.round(value * 100) / 100
         num=num.toFixed(2);
         return (formatNumber.new(num));

}
function formatoDecimalVacio(value, row, index) {
    if (row.codigo == null) {
        return ''
    } else {
        num=Math.round(value * 100) / 100
        num=num.toFixed(2);
        return (formatNumber.new(num));
    }

}
function formatoPPVenta(value, row, index) {
    if ( row.codigo == null) {
        return ''
    } else {

        num= (Math.round(row.totalVentasAcum * 100) / 100)  / (Math.round(row.fac * 100) / 100)
        if (!num) {
            num=''
        } else {
            num=num.toFixed(2);
            return (num) 
        }
    }
}

function totalVacio(value, row, index) {
    if (row.codigo == null) {
        value = ''
    }
    return (value);
}
function subTotal(value, row, index) {
    if (row.codigo == null && row.linea == null) {
        value = 'TOTAL GENERAL'
    }
    else if (row.codigo == null) {
        value = 'TOTAL ' + row.lineaS
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
                "font-weight": "bold",
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
    ini = iniciofecha.format('YYYY-MM-DD')
    fin = finfecha.format('YYYY-MM-DD')
    almText = $('#almacen_filtro').find(":selected").text();
    moneda = ($('#moneda').val() == 1) ? 'DOLARES' : 'BOLIVIANOS'
    
    $('#ragoFecha').text("DEL " + iniciofecha.format('DD/MM/YYYY') + "  AL  " + finfecha.format('DD/MM/YYYY'));
    $('#tituloReporte').text(almText);
    $('#monedaTitulo').text(moneda);
}
function restornardatosSelect(res) {


    let linea = new Array()
    let marca = new Array()
    let datos = new Array()

    $.each(res, function (index, value) {
        linea.push(value.lineaS)
        marca.push(value.linea)
    })

    linea.sort();
    marca.sort()
    datos.push(linea.unique());
    datos.push(marca.unique());
    return (datos);
}
Array.prototype.unique = function (a) {
    return function () {
        return this.filter(a)
    }
}(function (a, b, c) {
    return c.indexOf(a, b + 1) < 0
});
function GetSortOrder(prop) {  
    return function(a, b) {  
        if (a[prop] > b[prop]) {  
            return 1;  
        } else if (a[prop] < b[prop]) {  
            return -1;  
        }  
        return 0;  
    }  
}  