let iniciofecha = moment().subtract(1, 'month').startOf('month')
let finfecha = moment().subtract(1, 'month').endOf('month')

$(document).ready(function () {
    $('#export').click(function () {
        $('#tablaReporteEgresos').tableExport({
        type:'excel',
        fileName: 'Reporte Egresos',
        numbers: {output : false}
        })
      });
    $(".tiponumerico").inputmask({
        alias: "decimal",
        digits: 2,
        groupSeparator: ',',
        autoGroup: true,
        autoUnmask: true
    });

    let start = iniciofecha
    let end = finfecha

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
            //ranges:jsonrango
            ranges: {
                'Hoy': [moment(), moment()],
                'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
                'Mes Actual': [moment().subtract(0, 'month').startOf('month'), moment().subtract(0, 'month').endOf('month')],
                "Hace un Mes": [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'Hace dos Meses': [moment().subtract(2, 'month').startOf('month'), moment().subtract(2, 'month').endOf('month')],
                'Hace tres Meses': [moment().subtract(3, 'month').startOf('month'), moment().subtract(3, 'month').endOf('month')],
                'Gestion Actual': [moment().subtract(0, 'year').startOf('year'), moment().subtract(0, 'year').endOf('year')],
            }
        }, cb);

        cb(start, end);

    });
    $('#fechapersonalizada').on('apply.daterangepicker', function (ev, picker) {
        
        retornarReporteEgresos();
    });
    tituloReporte();
    retornarReporteEgresos();
})
$(document).on("change", "#almacen_filtro", function () {

    tituloReporte();
    retornarReporteEgresos();
})
$(document).on("change", "#tipo_filtro", function () {
    tituloReporte();
    retornarReporteEgresos();
})
$(document).on("click", "#refresh", function () {
    retornarReporteEgresos();
})
$(document).on("click", "#pdf", function () {
    let ini = iniciofecha.format('YYYY-MM-DD')
    let fin = finfecha.format('YYYY-MM-DD')
    let alm = $("#almacen_filtro").val() == '' ? '1' : $("#almacen_filtro").val()
    let tin = $("#tipo_filtro").val() == '' ? '1' : $("#tipo_filtro").val()
    let imprimir = base_url("pdf/ReportEgresos/index/") + ini + '/' + fin + '/' + alm + '/' + tin;
    console.log(imprimir);
    window.open(imprimir);
})


function retornarReporteEgresos() {
    tituloReporte();
    let ini = iniciofecha.format('YYYY-MM-DD')
    let fin = finfecha.format('YYYY-MM-DD')
    let alm = $("#almacen_filtro").val()
    let tipoingreso = $("#tipo_filtro").val()
    agregarcargando();
    $.ajax({
        type: "POST",
        url: base_url('index.php/Reportes/mostrarReporteEgresos'),
        dataType: "json",
        data: {
            i: ini,
            f: fin,
            a: alm,
            ti: tipoingreso
        },
    }).done(function (res) {
        for (let index = 0; index < res.length; index++) {
            /*if (res[index].id == null && res[index].tipomov == null && res[index].almacen == null && res[index].cliente == null) {
                res[index].descripcion = `TOTAL GENERAL`
                res[index].punitario = ''
                res[index].almacen = ''
                res[index].cliente = ''
                res[index].fechamov = ''
                res[index].nmov = ''
                res[index].codigo = ''
                res[index].uni = ''
                res[index].mon = ''

            } else if (res[index].id == null && res[index].tipomov == null  && res[index].cliente == null) {
                res[index].descripcion = `TOTAL ALMACEN ${res[index].nombreAlmacen}:`
                res[index].punitario = ''
                res[index].almacen = ''
                res[index].cliente = ''
                res[index].fechamov = ''
                res[index].nmov = ''
                res[index].codigo = ''
                res[index].uni = ''
                res[index].mon = ''

            } else*/
            if (res[index].id == null   && res[index].nmov == null) {
                res[index].descripcion = `TOTAL ${res[index].siglaMov.toUpperCase()}:`
                res[index].punitario = ''
                res[index].almacen = ''
                res[index].cliente = ''
                res[index].fechamov = ''
                res[index].nmov = ''
                res[index].codigo = ''
                res[index].uni = ''
                res[index].mon = ''

            } else if (res[index].id == null) {
                res[index].descripcion = ''
                res[index].punitario = ''
                res[index].almacen = ''
                res[index].cliente = ''
                res[index].fechamov = ''
                res[index].nmov = ''
                res[index].codigo = ''
                res[index].uni = ''
                res[index].mon = ''
            }
        }
        quitarcargando();
        //datosselect = restornardatosSelect(res);
        $("#tablaReporteEgresos").bootstrapTable('destroy');    
        $("#tablaReporteEgresos").bootstrapTable({ 
            data: res,
            striped: true,
            pagination: false,
            pageSize: "100",
            search: true,
            showColumns: true,
            filter: true,
            stickyHeader: true,
            stickyHeaderOffsetY: '50px',
            strictSearch: true,
            //showFooter: true,
            //footerStyle: footerStyle,
            rowStyle:rowStyle,
            columns: [
                {
                    field: 'almacen',
                    title: 'Almacen',
                    sortable: true,
                    searchable: false,
                    visible: (alm == '')? true: false,
                    align: 'center'
                },

                {
                    field: 'cliente',
                    title: tipoingreso == 8 ? 'Destino' : 'Cliente' ,
                    sortable: true,
                    searchable: false,
                    visible: true,
                    /*filter: {
                        type: "select",
                        data: datosselect[0]
                    }*/
                },
                {
                    field: 'fechamov',
                    title: 'Fecha',
                    searchable: false,
                    sortable: true,
                    formatter: formato_fecha_corta_sub
                },
               
                {
                    field: 'nmov',
                    title: 'N° Mov',
                    sortable: true,
                    align: 'center'
                },
                {
                    field: 'codigo',
                    title: 'Código',
                    sortable: true,
                    searchable: false,
                    /*filter: {
                        type: "select",
                        data: datosselect[1]
                      }*/
                },
                
                {
                    field: 'descripcion',
                    title: 'Descripción',
                    sortable: true,
                    searchable: false,
                },
                {
                    field: 'uni',
                    title: 'Unidad',
                    sortable: true,
                    searchable: false,
                },
                {
                    field: 'mon',
                    title: 'Moneda',
                    sortable: true,
                    searchable: false,
                },
                {
                    field: 'cantidad',
                    title: 'Cantidad',
                    sortable: true,
                    searchable: false,
                    formatter: operateFormatter3,
                    align: 'right',
                },
                {
                    field: 'punitario',
                    title: 'Precio Unit.',
                    sortable: true,
                    align: 'right',
                    searchable: false,
                    formatter: precioUnitarioFormatter

                },
                {
                    field: 'total',
                    title: 'Total BOB',
                    sortable: true,
                    searchable: false,
                    formatter: operateFormatter3,
                    align: 'right',
                },
                {
                    field: 'totalDolares',
                    title: 'Total $u$',
                    sortable: true,
                    visible: false,
                    searchable: false,
                    formatter: operateFormatter3,
                    align: 'right',
                },

            ]
          });

        
    
    
    }).fail(function (jqxhr, textStatus, error) {
        var err = textStatus + ", " + error;
        console.log("Request Failed: " + err);
    });
}
function rowStyle(row, index) {
    if (row.id==null) {
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
    almText = $('#almacen_filtro').find(":selected").text();
    tipoText = $('#tipo_filtro').find(':selected').text();
    $('#tituloAlmacen').text(almText);
    $('#tituloTipo').text(tipoText);
    $('#ragoFecha').text("DEL " + iniciofecha.format('DD/MM/YYYY') + "  AL  " + finfecha.format('DD/MM/YYYY'));

}

function precioUnitarioFormatter(value, row, index) {
    if (row.punitario == '') {
        ret = ''
    } else {
        num = Math.round(value * 100) / 100
    num = num.toFixed(2);
    ret = (formatNumber.new(num));
    }
    return ret
    
}
function operateFormatter3(value, row, index) {
    num = Math.round(value * 100) / 100
    num = num.toFixed(2);
    return (formatNumber.new(num));
}


  
