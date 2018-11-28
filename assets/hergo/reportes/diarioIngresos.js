var iniciofecha = moment().subtract(0, 'year').startOf('year')
var finfecha = moment().subtract(0, 'year').endOf('year')

$(document).ready(function () {
    $(".tiponumerico").inputmask({
        alias: "decimal",
        digits: 2,
        groupSeparator: ',',
        autoGroup: true,
        autoUnmask: true
    });

    var start = moment().subtract(0, 'year').startOf('year')
    var end = moment().subtract(0, 'year').endOf('year')
    var actual = moment().subtract(0, 'year').startOf('year')
    var unanterior = moment().subtract(1, 'year').startOf('year')
    var dosanterior = moment().subtract(2, 'year').startOf('year')
    var tresanterior = moment().subtract(3, 'year').startOf('year')

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
                'Gestion Actual': [moment().subtract(0, 'year').startOf('year'), moment().subtract(0, 'year').endOf('year')],
                "Hace un Año": [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')],
                'Hace dos Años': [moment().subtract(2, 'year').startOf('year'), moment().subtract(2, 'year').endOf('year')],
                'Hace tres Años': [moment().subtract(3, 'year').startOf('year'), moment().subtract(3, 'year').endOf('year')],
                /*'Hoy': [moment(), moment()],
                'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],               
                'Este Mes': [moment().startOf('month'), moment().endOf('month')],*/

            }
        }, cb);

        cb(start, end);

    });
    $('#fechapersonalizada').on('apply.daterangepicker', function (ev, picker) {
        
        retornarTablaIngresos();
    });
    tituloReporte();
    retornarTablaIngresos();
})
$(document).on("change", "#almacen_filtro", function () {

    tituloReporte();
    retornarTablaIngresos();
})
$(document).on("change", "#tipo_filtro", function () {
    tituloReporte();
    retornarTablaIngresos();
})



function retornarTablaIngresos() {
    var ini = iniciofecha.format('YYYY-MM-DD')
    var fin = finfecha.format('YYYY-MM-DD')
    var alm = $("#almacen_filtro").val()
    var tipoingreso = $("#tipo_filtro").val()
    agregarcargando();
    $.ajax({
        type: "POST",
        url: base_url('index.php/Reportes/mostrarDiarioIngresos'),
        dataType: "json",
        data: {
            i: ini,
            f: fin,
            a: alm,
            ti: tipoingreso
        },
    }).done(function (res) {
        quitarcargando();
        datosselect = restornardatosSelect(res);
        $("#tablaDiarioIngresos").bootstrapTable('destroy');    
        $("#tablaDiarioIngresos").bootstrapTable({ ////********cambiar nombre tabla viata
            data: res,
            striped: true,
            pagination: true,
            pageSize: "100",
            search: true,
            showColumns: true,
            filter: true,
            stickyHeader: true,
            stickyHeaderOffsetY: '50px',
            showFooter: true,
            footerStyle: footerStyle,
            columns: [
                {
                    field: 'almacen',
                    title: 'Almacen',
                    sortable: true,
                    visible: false,
                    align: 'center'
                },
                {
                    field: 'nmov',
                    title: 'N° Mov',
                    sortable: true,
                    align: 'center,',
                    filter: {
                        type: "input"
                    }
                },
                {
                    field: 'fechamov',
                    title: 'Fecha',
                    sortable: true,
                    formatter: formato_fecha_corta
                },
                {
                    field: 'nmov',
                    title: 'N° Mov',
                    sortable: true,
                    align: 'center'
                },
                {
                    field: 'ordcomp',
                    title: 'N° Pedido',
                    sortable: true,
                    align: 'center'
                },
                {
                    field: 'nombreproveedor',
                    title: 'Proveedor',
                    sortable: true,
                    filter: {
                        type: "select",
                        data: datosselect[0]
                    }
                },
                {
                    field: 'CodigoArticulo',
                    title: 'Código',
                    sortable: true,
                    filter: {
                        type: "select",
                        data: datosselect[1]
                      }
                },
                
                {
                    field: 'Descripcion',
                    title: 'Descripción',
                    sortable: true,
                },
                {
                    field: 'Unidad',
                    title: 'Unidad',
                    sortable: true,
                },
                {
                    field: 'cantidad',
                    title: 'Cantidad',
                    sortable: true,
                    formatter: operateFormatter3,
                    filter: {
                        type: "input"
                    }
                },
                {
                    field: 'punitario',
                    title: 'Precio Unit.',
                    sortable: true,
                    formatter: operateFormatter3

                },
                {
                    field: 'total',
                    title: 'Total',
                    sortable: true,
                    formatter: operateFormatter3,
                    footerFormatter: sumaColumna
                },
                {
                    field: 'obs',
                    title: 'Observaciones',
                    sortable: true,
                }

            ]
          });
    
    
    }).fail(function (jqxhr, textStatus, error) {
        var err = textStatus + ", " + error;
        console.log("Request Failed: " + err);
    });
}



function tituloReporte() {
    almText = $('#almacen_filtro').find(":selected").text();
    tipoText = $('#tipo_filtro').find(':selected').text();
    $('#tituloAlmacen').text(almText);
    $('#tituloTipo').text(tipoText);
    $('#ragoFecha').text("DEL " + iniciofecha.format('DD/MM/YYYY') + "  AL  " + finfecha.format('DD/MM/YYYY'));

}

function operateFormatter3(value, row, index) {
    num = Math.round(value * 100) / 100
    num = num.toFixed(2);
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
  
function restornardatosSelect(res) {
    var provedor = new Array()
    var codigo = new Array()
    var datos = new Array()
    var destino = new Array()
    $.each(res, function (index, value) {
    provedor.push(value.nombreproveedor)
    codigo.push(value.CodigoArticulo)
    destino.push(value.destino)
    })
    provedor.sort();
    codigo.sort();
    datos.push(provedor.unique());
    datos.push(codigo.unique());
    datos.push(destino.unique());
    return (datos);
  }
  Array.prototype.unique = function (a) {
    return function () {
      return this.filter(a)
    }
  }(function (a, b, c) {
    return c.indexOf(a, b + 1) < 0
});

