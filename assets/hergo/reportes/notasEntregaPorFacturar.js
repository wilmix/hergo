var iniciofecha = moment().subtract(5, 'year').startOf('year')
var finfecha = moment().subtract(0, 'year').endOf('year')
$(document).ready(function () {
    $('#export').click(function () {
        $('#tablaNotasEntregaFacturar').tableExport({
        type:'excel',
        fileName: 'NotasEntregaPendientesPago',
        numbers: {output : false}
        })
      });
    tituloReporte()
    $(".tiponumerico").inputmask({
        alias: "decimal",
        digits: 2,
        groupSeparator: ',',
        autoGroup: true,
        autoUnmask: true
    });

    var start = moment().subtract(5, 'year').startOf('year')
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
            ranges: {
                'Gestion Actual': [moment().subtract(0, 'year').startOf('year'), moment().subtract(0, 'year').endOf('year')],
                "Hace un Año": [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')],
                'Hace dos Años': [moment().subtract(2, 'year').startOf('year'), moment().subtract(2, 'year').endOf('year')],
                'Hace tres Años': [moment().subtract(3, 'year').startOf('year'), moment().subtract(3, 'year').endOf('year')],
            }
        }, cb);

        cb(start, end);

    });
    $('#fechapersonalizada').on('apply.daterangepicker', function (ev, picker) {
        retornarNEporFac();
    });
    retornarNEporFac();
})
$(document).on("change", "#almacen_filtro", function () {
    retornarNEporFac();
})
$(document).on("change", "#tipoNota", function () {
    retornarNEporFac();
})
$(document).on("click", "#refresh", function () {
    retornarNEporFac();
})



function retornarNEporFac() //*******************************
{
    tituloReporte()
    let ini = iniciofecha.format('YYYY-MM-DD')
    let fin = finfecha.format('YYYY-MM-DD')
    let alm = $("#almacen_filtro").val();
    let tipoNota = $("#tipoNota").val();
    agregarcargando();
    $.ajax({
        type: "POST",
        url: base_url('index.php/Reportes/mostrarNEporFac'), //******controlador
        dataType: "json",
        data: {
            ini: ini,
            fin: fin,
            alm: alm,
            c:'all',
            tipoNota: tipoNota

        }, //**** variables para filtro
    }).done(function (res) {
        quitarcargando();
       console.log(`Almacen ${alm} ini ${ini} fin ${fin}`);
        datosselect = restornardatosSelect(res);
        $("#tablaNotasEntregaFacturar").bootstrapTable('destroy');
        $("#tablaNotasEntregaFacturar").bootstrapTable({ ////********cambiar nombre tabla viata

            data: res,
            striped: true,
            pagination: true,
            pageSize: "100",
            search: true,
            searchOnEnterKey: true,
            showColumns: true,
            filter: true,
            stickyHeader: true,
            stickyHeaderOffsetY: '50px',
            rowStyle:rowStyle,
            //showFooter: true,
            //footerStyle: footerStyle,
            columns: [{
                    field: 'fechamov',
                    title: 'Fecha',
                    sortable: true,
                    //formatter: cellEmpy,
                    formatter: formato_fecha_corta_sub_ne
                },
                {
                    field: 'almacen',
                    title: 'Almacen',
                    visible: alm == '' ? true : false,
                    sortable: true,
                    formatter: cellEmpy,
                    filter: {
                        type: "select",
                        data: datosselect[0]
                    }
                },
                {
                    field: 'nombreCliente',
                    title: 'Cliente',
                    visible: true,
                    sortable: true,
                    filter: {
                        type: "select",
                        data: datosselect[1]
                    }
                },
                {
                    field: 'n',
                    title: 'Número',
                    visible: true,
                    sortable: true,
                    align: 'center',
                    filter: {
                        type: "input"
                    },
                    formatter: cellEmpy
                },
                {
                    field: 'total',
                    title: 'Importe Bs',
                    visible: true,
                    sortable: true,
                    align: 'right',
                    formatter: operateFormatter3,
                    footerFormatter: sumaColumna
                },
                {
                    field: 'totalDol',
                    title: 'Importe $U$',
                    visible: false,
                    sortable: true,
                    align: 'right',
                    formatter: operateFormatter3,
                    footerFormatter: sumaColumna
                },
                {
                    field: 'glosa',
                    title: 'Glosa',
                    visible: false,
                    sortable: true,
                    formatter: cellEmpy,
                    align: 'right',
                },
                {
                    field: 'autor',
                    title: 'Responsable',
                    visible: true,
                    sortable: true,
                    align: 'center',
                    formatter: cellEmpy,
                    filter: {
                        type: "select",
                        data: datosselect[2]
                    }
                },
                {
                    field: 'monedasigla',
                    title: 'Moneda',
                    align: 'Moneda',
                    formatter: cellEmpy,
                    visible: false
                }
            ]
        });
    }).fail(function (jqxhr, textStatus, error) {
        var err = textStatus + ", " + error;
        console.log("Request Failed: " + err);
    });
}

function rowStyle(row, index) {
    if (row.idEgresos==null) {
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
function formato_fecha_corta_sub_ne(value, row, index) {
    let fecha = ''
    if (row.idEgresos==null) {
      fecha = ''
    } else {
      if ((value == "0000-00-00 00:00:00") || (value == "") || (value == null))
        fecha = "sin fecha de registro"
      else
        fecha = moment(value, "YYYY/MM/DD HH:mm:ss").format("DD/MM/YYYY")
    }
    return fecha
}
function tituloReporte() {
    almText = $('#almacen_filtro').find(":selected").text();
    $('#tituloReporte').text(almText);
    $('#ragoFecha').text("DEL " + iniciofecha.format('DD/MM/YYYY') + "  AL  " + finfecha.format('DD/MM/YYYY'));
}

function operateFormatter3(value, row, index) {
    num = Math.round(value * 100) / 100
    num = num.toFixed(2);
    return (formatNumber.new(num));
}

function cellEmpy(value, row, index){
    let valor
    if (row.idEgresos==null) {
        valor = ''
    } else {
        valor = value
    }
    return valor
}

function sumaColumna(data) {
    field = this.field;
    let totalSum = data.reduce(function (sum, row) {
        return sum + (+row[field]);
    }, 0);
    return (formatNumber.new(totalSum.toFixed(2)));
}

function restornardatosSelect(res) {

    var alm = new Array()
    var cliente = new Array()
    var responsable = new Array()
    var datos = new Array()
    $.each(res, function (index, value) {

        alm.push(value.almacen)
        if (value.nombreCliente != null && value.idEgresos == null) {
            cliente.push(value.nombreCliente)
        }
        responsable.push(value.autor)
    })

    alm.sort();
    cliente.sort();
    responsable.sort();
    //console.log(nPago);
    datos.push(alm.unique());
    datos.push(cliente.unique());
    datos.push(responsable.unique());
    return (datos);
}
Array.prototype.unique = function (a) {
    return function () {
        return this.filter(a)
    }
}(function (a, b, c) {
    return c.indexOf(a, b + 1) < 0
});