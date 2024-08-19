let iniciofecha = moment().subtract(1, 'month').startOf('month')
let finfecha = moment().subtract(1, 'month').endOf('month')

$(document).ready(function () {
    $('#export').click(function () {
        $('#tablaReporteIngresos').tableExport({
        type:'excel',
        fileName: 'Reporte Ingresos',
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
        
        retornarReporteIngresos();
    });
    tituloReporte();
    retornarReporteIngresos();
})
$(document).on("change", "#almacen_filtro", function () {

    tituloReporte();
    retornarReporteIngresos();
})
$(document).on("change", "#tipo_filtro", function () {
    tituloReporte();
    retornarReporteIngresos();
})
$(document).on("click", "#pdf", function () {
    let ini = iniciofecha.format('YYYY-MM-DD')
    let fin = finfecha.format('YYYY-MM-DD')
    let alm = $("#almacen_filtro").val() == '' ? '1' : $("#almacen_filtro").val()
    let tin = $("#tipo_filtro").val() == '' ? '1' : $("#tipo_filtro").val()
    let imprimir = base_url("pdf/ReportIngreso/index/") + ini + '/' + fin + '/' + alm + '/' + tin;
    window.open(imprimir);
})


function retornarReporteIngresos() {
    tituloReporte();
    let ini = iniciofecha.format('YYYY-MM-DD')
    let fin = finfecha.format('YYYY-MM-DD')
    let alm = $("#almacen_filtro").val()
    let tipoingreso = $("#tipo_filtro").val()
    agregarcargando();
    $.ajax({
        type: "POST",
        url: base_url('index.php/Reportes/mostrarReporteIngreso'),
        dataType: "json",
        data: {
            i: ini,
            f: fin,
            a: alm,
            ti: tipoingreso
        },
    }).done(function (res) {
        for (let index = 0; index < res.length; index++) {
             if (res[index].id == null   && res[index].nmov == null) {
                res[index].descripcion = `${res[index].siglaMov}:`
                res[index].punitario = ''
                res[index].almacen = ''
                res[index].provedor = ''
                res[index].fechamov = ''
                res[index].nmov = ''
                res[index].codigo = ''
                res[index].uni = ''
                res[index].mon = ''

            } else if (res[index].id == null) {
                res[index].descripcion = ``
                res[index].punitario = ''
                res[index].almacen = ''
                res[index].provedor = ''
                res[index].fechamov = ''
                res[index].nmov = ''
                res[index].codigo = ''
                res[index].uni = ''
                res[index].mon = ''
            }
        }
        quitarcargando();
        //datosselect = restornardatosSelect(res);
        $("#tablaReporteIngresos").bootstrapTable('destroy');    
        $("#tablaReporteIngresos").bootstrapTable({ 
            data: res,
            striped: true,
            pagination: false,
            pageSize: "100",
            search: true,
            showColumns: true,
            filter: true,
            stickyHeader: true,
            stickyHeaderOffsetY: '50px',
            //showFooter: true,
            //footerStyle: footerStyle,
            rowStyle:rowStyle,
            columns: [
                {
                    field: 'almacen',
                    title: 'Almacen',
                    sortable: true,
                    visible: false,
                    align: 'center'
                },
                {
                    field: 'almacenOrigen',
                    title: 'Origen',
                    sortable: true,
                    visible: tipoingreso == 3 ? true : false,
                    align: 'center'
                },

                {
                    field: 'provedor',
                    title: 'Proveedor',
                    sortable: true,
                    visible: tipoingreso == 3 ? false : true,
                    /*filter: {
                        type: "select",
                        data: datosselect[0]
                    }*/
                },
                {
                    field: 'fechamov',
                    title: 'Fecha',
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
                    /*filter: {
                        type: "select",
                        data: datosselect[1]
                      }*/
                },
                
                {
                    field: 'descripcion',
                    title: 'Descripción',
                    sortable: true,
                },
                {
                    field: 'uni',
                    title: 'Unidad',
                    sortable: true,
                },
                {
                    field: 'mon',
                    title: 'Moneda',
                    sortable: true,
                },
                {
                    field: 'cantidad',
                    title: 'Cantidad',
                    sortable: true,
                    formatter: operateFormatter3,
                    align: 'right',
                },
                {
                    field: 'punitario',
                    title: 'Precio Unit.',
                    sortable: true,
                    align: 'right',
                    formatter: precioUnitarioFormatter

                },
                {
                    field: 'total',
                    title: 'Total',
                    sortable: true,
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


  
