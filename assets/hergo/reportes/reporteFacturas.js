let iniciofecha = moment().year(gestionActual).startOf('year')
let finfecha = moment().year(gestionActual).endOf('year')

$(document).ready(function () {
    $('#export').click(function () {
        $('#tablaReporteFacturas').tableExport({
        type:'excel',
        fileName: 'Reporte Facturas',
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
            ranges: {
                'Hoy': [moment(), moment()],
                "Mes Actual": [moment().subtract(0, 'month').startOf('month'), moment().subtract(0, 'month').endOf('month')],
                "Hace un mes": [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'Hace dos meses': [moment().subtract(2, 'month').startOf('month'), moment().subtract(2, 'month').endOf('month')],
                'Hace tres meses': [moment().subtract(3, 'month').startOf('month'), moment().subtract(3, 'month').endOf('month')],
                'Gestion Actual': [moment().subtract(0, 'year').startOf('year'), moment().subtract(0, 'year').endOf('year')],
            }
        }, cb);

        cb(start, end);

    });
    $('#fechapersonalizada').on('apply.daterangepicker', function (ev, picker) {
        
        retornarReporteFacturas();
    });
    tituloReporte();
    retornarReporteFacturas();
})
$(document).on("change", "#almacen_filtro", function () {

    tituloReporte();
    retornarReporteFacturas();
})
$(document).on("change", "#moneda", function () {
    retornarReporteFacturas();
})
$(document).on("click", "#refresh", function () {
    retornarReporteFacturas();
})



function retornarReporteFacturas() {
    tituloReporte();
    let ini = iniciofecha.format('YYYY-MM-DD')
    let fin = finfecha.format('YYYY-MM-DD')
    let alm = $("#almacen_filtro").val()
    let mon = $("#moneda").val()
    agregarcargando();
    $.ajax({
        type: "POST",
        url: base_url('index.php/Reportes/mostrarReporteFacturas'),
        dataType: "json",
        data: {
            i: ini,
            f: fin,
            a: alm,
        },
    }).done(function (res) {
        for (let index = 0; index < res.length; index++) {
            if (res[index].id == null && res[index].almacen == null && res[index].fechaFac == null) {
                res[index].descr = `TOTAL GENERAL`
                res[index].pu = ''
                res[index].almacen = ''
                res[index].cliente = ''
                res[index].fechaFac = ''
                res[index].nFactura = ''
                res[index].codigo = ''
                res[index].pu = ''
                res[index].lote = ''
                res[index].cantidad = ''
                res[index].uni = '-'
            } else if (res[index].id == null && res[index].fechaFac == null) {
                res[index].descr = `TOTAL ALMACEN ${res[index].almacen}:`
                res[index].pu = ''
                res[index].almacen = ''
                res[index].cliente = ''
                res[index].fechaFac = ''
                res[index].nFactura = ''
                res[index].codigo = ''
                res[index].pu = ''
                res[index].lote = ''
                res[index].cantidad = ''
                res[index].uni = '-'
            }  else if (res[index].id == null) {
                res[index].descr = `TOTAL ${formato_fecha_corta(res[index].fechaFac)}:`
                res[index].pu = ''
                res[index].almacen = ''
                res[index].cliente = ''
                res[index].fechaFac = ''
                res[index].nFactura = ''
                res[index].codigo = ''
                res[index].pu = ''
                res[index].lote = ''
                res[index].cantidad = ''
                res[index].uni = '-'

            }
        }
        quitarcargando();
        //datosselect = restornardatosSelect(res);
        $("#tablaReporteFacturas").bootstrapTable('destroy');    
        $("#tablaReporteFacturas").bootstrapTable({ 
            data: res,
            striped: true,
            pagination: false,
            pageSize: "100",
            search: true,
            showColumns: true,
            filter: true,
            stickyHeader: true,
            stickyHeaderOffsetY: '50px',
            rowStyle:rowStyle,
            columns: [
                {
                    field: 'almacen',
                    title: 'Almacen',
                    visible: alm == '' ? true: false,
                    align: 'center'
                },

                {
                    field: 'cliente',
                    title: 'Cliente',
                    sortable: true,
                    visible: false,
                    /*filter: {
                        type: "select",
                        data: datosselect[0]
                    }*/
                },
                {
                    field: 'fechaFac',
                    title: 'Fecha',
                    //sortable: true,
                    formatter: formato_fecha_corta_sub
                },
                {
                    field: 'lote',
                    title: 'Lote',
                    align: 'center'
                },
                {
                    field: 'nFactura',
                    title: 'Número',
                    sortable: true,
                    align: 'center'
                },
                {
                    field: 'codigo',
                    title: 'Código',
                    //sortable: true,
                    /*filter: {
                        type: "select",
                        data: datosselect[1]
                      }*/
                },
                
                {
                    field: 'descr',
                    title: 'Descripción',
                    //sortable: true,
                },
                {
                    field: 'uni',
                    title: 'Unidad',
                    align:'center'
                    //sortable: true,
                },
                {
                    field: 'cantidad',
                    title: 'Cantidad',
                    //sortable: true,
                    formatter:operateFormatter3,
                    align: 'right',
                },
                {
                    field: 'pu',
                    title: 'P/U BOB',
                    //sortable: true,
                    align: 'right',
                    formatter: precioUnitarioFormatter,
                    visible: mon==1 ? false  : true
                },
                {
                    field: 'puDolares',
                    title: 'P/U $U$',
                    //sortable: true,
                    align: 'right',
                    formatter: precioUnitarioFormatter,
                    visible: mon==1 ? true  : false

                },
                {
                    field: 'total',
                    title: 'Total BOB',
                    //sortable: true,
                    formatter: operateFormatter3,
                    align: 'right',
                    visible: mon==1 ? false  : true
                },
                {
                    field: 'totalDolares',
                    title: 'Total $U$',
                    //sortable: true,
                    formatter: operateFormatter3,
                    align: 'right',
                    visible: mon==1 ? true  : false
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


  
