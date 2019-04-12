let iniciofecha = moment().subtract(0, 'year').startOf('year')
let finfecha = moment().subtract(0, 'year').endOf('year')

$(document).ready(function () {
    $('#export').click(function () {
        $('#tblReportePagos').tableExport({
        type:'excel',
        fileName: 'Reporte Pagos',
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
                'Gestion Actual': [moment().subtract(0, 'year').startOf('year'), moment().subtract(0, 'year').endOf('year')],
                'Hace un Año': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')],
            }
        }, cb);

        cb(start, end);

    });
    $('#fechapersonalizada').on('apply.daterangepicker', function (ev, picker) {
        returnReportePago();
    });
    returnReportePago();
})
$(document).on("change", "#almacen_filtro", function () {
    returnReportePago();
})
$(document).on("change", "#moneda", function () {
    returnReportePago();
})
$(document).on("click", "#refresh", function () {
    returnReportePago();
})
function returnReportePago() {
    tituloReporte();
    let ini = iniciofecha.format('YYYY-MM-DD')
    let fin = finfecha.format('YYYY-MM-DD')
    let alm = $("#almacen_filtro").val()
    // let mon = $("#moneda").val()
    agregarcargando();
    $.ajax({
        type: "POST",
        url: base_url('index.php/Reportes/showReportPagos'),
        dataType: "json",
        data: {
            i: ini,
            e: fin,
            a: alm,
        },
    }).done(function (res) {
        quitarcargando();
        datosselect = restornardatosSelect(res);
        $("#tblReportePagos").bootstrapTable('destroy');    
        $("#tblReportePagos").bootstrapTable({ 
            data: res,
            striped: true,
            pagination: true,
            pageSize: "100",
            search: true,
            showColumns: true,
            filter: true,
            stickyHeader: true,
            stickyHeaderOffsetY: '50px',
            strictSearch: true,
            rowStyle:rowStyle,
            columns: [
                {
                    field: 'almPago',
                    title: 'Almacen Pago',
                    visible: alm == '' ? true: false,
                    align: 'center',
                    searchable: false,
                },
                {
                    field: 'fechaPago',
                    title: 'Fecha Pago',
                    sortable: true,
                    align: 'center',
                    searchable: false,
                },

                {
                    field: 'numPago',
                    title: 'Nº Pago',
                    align: 'center',
                    sortable: true,
                },
                {
                    field: 'clienteCab',
                    title: 'Cliente Pago',
                    sortable: true,
                    searchable: false,
                    visible:false

                },
                {
                    field: 'almFac',
                    title: 'Alm Factura',
                    searchable: false,
                    visible:false
                },
                {
                    field: 'fechaFac',
                    title: 'Fecha Factura',
                    sortable: true,
                    align: 'center',
                    searchable: false,
                },
                {
                    field: 'nFactura',
                    title: 'Nº Factura',
                    sortable: true,
                    align: 'center',
                    filter: {
                        type: "select",
                        data: datosselect[1]
                    }
                },
                {
                    field: 'ClienteFactura',
                    title: 'Cliente',
                    sortable: true,
                    filter: {
                        type: "select",
                        data: datosselect[0]
                    }

                },
                {
                    field: 'monto',
                    title: 'Monto',
                    sortable: true,
                    formatter:operateFormatter3,
                    align: 'right',
                    searchable: false,
                },
                {
                    field: 'montoRaw',
                    title: '',
                    visible:false
                },

            ]
          });
    }).fail(function (jqxhr, textStatus, error) {
        var err = textStatus + ", " + error;
        console.log("Request Failed: " + err);
    });
}
function rowStyle(row, index) {
    if (row.codigo=='') {
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
    tipoText = $('#tipo_filtro').find(':selected').text();
    let ini = iniciofecha.format('DD/MM/YYYY')
    let fin = finfecha.format('DD/MM/YYYY')

    $('#tituloAlmacen').text(almText);
    $('#tituloTipo').text(tipoText);
    $('#ragoFecha').text("DEL " + ini + "  AL  " + fin);

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
function restornardatosSelect(res) {

    let cliente = new Array()
    let nFac = new Array()
    let datos = new Array()
    $.each(res, function (index, value) {

        cliente.push(value.ClienteFactura)
        nFac.push(value.nFactura)
    })

    cliente.sort()
    nFac.sort()
    datos.push(cliente.unique());
    datos.push(nFac.unique());

    return (datos);
}
Array.prototype.unique = function (a) {
    return function () {
        return this.filter(a)
    }
}(function (a, b, c) {
    return c.indexOf(a, b + 1) < 0
});

  
