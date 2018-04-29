var iniciofecha = moment().subtract(5, 'year').startOf('year')
var finfecha = moment().subtract(0, 'year').endOf('year')
$(document).ready(function () {
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
        tituloReporte();
        retornarFacturasPendientes();
    });
    tituloReporte();
    retornarFacturasPendientes();
})

$(document).on("change", "#almacen_filtro", function () {
    tituloReporte()
    retornarFacturasPendientes();
}) //para cambio filtro segun cada uno


function tituloReporte() {
    almText = $('#almacen_filtro').find(":selected").text();
    $('#tituloReporte').text(almText);
    $('#ragoFecha').text("DEL " + iniciofecha.format('DD/MM/YYYY') + "  AL  " + finfecha.format('DD/MM/YYYY'));
}

function retornarFacturasPendientes() //*******************************
{
    ini = iniciofecha.format('YYYY-MM-DD')
    fin = finfecha.format('YYYY-MM-DD')
    alm = $("#almacen_filtro").val();
    agregarcargando();
    $.ajax({
        type: "POST",
        url: base_url('index.php/Reportes/mostrarFacturasPendientesPago'), //******controlador
        dataType: "json",
        data: {
            i: ini,
            f: fin,
            a: alm
        }, //**** variables para filtro
    }).done(function (res) {
        quitarcargando();
        console.log(res);
        console.log(`Almacen ${alm} ini ${ini} fin ${fin}`);
        datosselect = restornardatosSelect(res);
        $("#tablaFacturasPendientes").bootstrapTable('destroy');
        $("#tablaFacturasPendientes").bootstrapTable({ ////********cambiar nombre tabla viata

            data: res,
            striped: true,
            pagination: true,
            pageSize: "10",
            search: true,
            searchOnEnterKey: true,
            showColumns: true,
            filter: true,
            showExport: true,
            exportTypes: ['xlsx'],
            exportDataType: 'basic',
            stickyHeader: true,
            stickyHeaderOffsetY: '50px',
            showFooter: true,
            footerStyle: footerStyle,
            columns: 
            [
                {
                    field: 'alm',
                    title: 'Almacen',
                    sortable: true,
                    visible: false
                },
                {
                    field: 'lote',
                    title: 'Lote',
                    sortable: true,
                    align: 'center'
                },
                {
                    field: 'nFac',
                    title: 'N° Factura',
                    sortable: true,
                    align: 'center',
                },
                {
                    field: 'fecha',
                    title: 'Fecha',
                    sortable: true,
                    align: 'center',
                    formatter: formato_fecha_corta
                },
                {
                    field: 'cliente',
                    title: 'Cliente',
                    sortable: true,
                    filter: {
                        type: "select",
                        data: datosselect[0]
                    }
                },
                {
                    field: 'totalFactura',
                    title: 'Crédito',
                    sortable: true,
                    align: 'right',
                    formatter: operateFormatter3,
                    footerFormatter: sumaColumna
                },
                {
                    field: 'NULL',
                    title: 'Abono',
                    sortable: true,
                    align: 'right',
                    formatter: operateFormatter3,
                    footerFormatter: sumaColumna
                },
                {
                    field: 'glosa',
                    title: 'Glosa',
                    sortable: true,
                }
            ]
        });
    }).fail(function (jqxhr, textStatus, error) {
        var err = textStatus + ", " + error;
        console.log("Request Failed: " + err);
    });
}
function operateFormatter3(value, row, index) {
    num = Math.round(value * 100) / 100
    num = num.toFixed(2);
    return (formatNumber.new(num));
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
function sumaColumna(data) {
    field = this.field;
    let totalSum = data.reduce(function (sum, row) {
        return sum + (+row[field]);
    }, 0);
    return (formatNumber.new(totalSum.toFixed(2)));
}
function restornardatosSelect(res) {

    var cliente = new Array()
    var datos = new Array()
    $.each(res, function (index, value) {

        cliente.push(value.cliente)
    })

    //alm.sort();
    cliente.sort();
    //responsable.sort();
    //console.log(nPago);
    //datos.push(alm.unique());
    datos.push(cliente.unique());
    //datos.push(responsable.unique());
    return (datos);
}
Array.prototype.unique = function (a) {
    return function () {
        return this.filter(a)
    }
}(function (a, b, c) {
    return c.indexOf(a, b + 1) < 0
});