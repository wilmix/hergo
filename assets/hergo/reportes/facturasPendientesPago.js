let iniciofecha = moment().subtract(10, 'year').startOf('year')
let finfecha = moment().subtract(0, 'year').endOf('year')
$(document).ready(function () {
    $('#export').click(function () {
        $('#tablaFacturasPendientes').tableExport({
        type:'excel',
        fileName: 'FacturasPendientesPago',
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

    let start = moment().subtract(10, 'year').startOf('year')
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
                'Gestion Actual': [moment().subtract(0, 'year').startOf('year'), moment().subtract(0, 'year').endOf('year')],
                "Hace un Año": [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')],
                'Hace dos Años': [moment().subtract(2, 'year').startOf('year'), moment().subtract(2, 'year').endOf('year')],
                'Todo': [moment().subtract(10, 'year').startOf('year'), moment().subtract(0, 'year').endOf('year')],
            }
        }, cb);

        cb(start, end);

    });
    $('#fechapersonalizada').on('apply.daterangepicker', function (ev, picker) {
        retornarFacturasPendientes();
    });
    retornarFacturasPendientes();
})
$(document).on("change", "#almacen_filtro", function () {
    retornarFacturasPendientes();
}) 

$(document).on("change", "#almacen_filtro", function () {
    retornarFacturasPendientes();
}) 
$(document).on("change", "#moneda", function () {
    retornarFacturasPendientes();
}) 
$(document).on("click", "#pendientes", function () {
    retornarFacturasPendientes();
})

function tituloReporte() {
    almText = $('#almacen_filtro').find(":selected").text();
    $('#tituloReporte').text(almText);
    $('#ragoFecha').text("DEL " + iniciofecha.format('DD/MM/YYYY') + "  AL  " + finfecha.format('DD/MM/YYYY'));
}

function retornarFacturasPendientes()
{
    tituloReporte()
    let ini = iniciofecha.format('YYYY-MM-DD')
    let fin = finfecha.format('YYYY-MM-DD')
    let almacen = $("#almacen_filtro").val()
    let tc = $("#moneda").val()
    agregarcargando();
    $.ajax({
        type: "POST",
        url: base_url('index.php/Reportes/mostrarFacturasPendientesPago'),
        dataType: "json",
        data: {
            almacen : almacen,
            ini: ini,
            fin: fin
        }, 
    }).done(function (res) {
        quitarcargando();
        datosselect = restornardatosSelect(res);
        $("#tablaFacturasPendientes").bootstrapTable('destroy');
        $("#tablaFacturasPendientes").bootstrapTable({ 
            data: res,
            striped: true,
            showColumns: true,
            search: true,
            filter: true,
            stickyHeader: true,
            stickyHeaderOffsetY: '50px',
            rowStyle:rowStyle,
            columns: 
            [
                {
                    field: 'almacen',
                    title: 'Almacen',
                    width:'150px',
                    visible: almacen==''?true:false
                },
                {
                    field: 'lote',
                    title: 'Lote',
                    width:'50px',
                    align: 'center'
                },
                {
                    field: 'nFactura',
                    title: 'N° Factura',
                    width:'80px',
                    align: 'center',
                },
                {
                    field: 'fechaFac',
                    title: 'Fecha',
                    width:'100px',
                    align: 'center',
                    formatter: formato_fecha_corta_sub
                },
                {
                    field: 'cliente',
                    title: 'Cliente',
                    filter: {
                        type: "select",
                        data: datosselect[0]
                    }
                },
                {
                    field: 'total',
                    title: 'Crédito',
                    sortable: true,
                    visible: tc ==1 ? false : true,
                    align: 'right',
                    width:'100px',
                    formatter: operateFormatter3,
                },
                {
                    field: 'montoPagado',
                    title: 'Abono',
                    visible: tc ==1 ? false : true,
                    align: 'right',
                    width:'100px',
                    formatter: operateFormatter3,
                },
                {
                    field: 'totalFacDol',
                    title: 'Crédito $u$',
                    sortable: true,
                    visible: tc ==1 ? true : false,
                    align: 'right',
                    width:'100px',
                    formatter: operateFormatter3,
                },
                {
                    field: 'montoPagoDol',
                    title: 'Abono $u$',
                    visible: tc ==1 ? true : false,
                    align: 'right',
                    width:'100px',
                    formatter: operateFormatter3,
                },
                {
                    field: 'saldo',
                    title: 'Saldo',
                    visible: tc ==1 ? false : true,
                    align: 'right',
                    width:'100px',
                    formatter: operateFormatter3,
                },
                {
                    field: 'saldoDol',
                    title: 'Saldo $u$',
                    //sortable: true,
                    align: 'right',
                    visible: tc ==1 ? true : false,
                    width:'100px',
                    formatter: operateFormatter3,
                },
                {
                    field: 'vendedor',
                    title: 'Responsable',
                    sortable: true,
                    width:'200px',
                    align: 'center',
                },
                
            ]
        });
    }).fail(function (jqxhr, textStatus, error) {
        let err = textStatus + ", " + error;
        console.log("Request Failed: " + err);
    });
}
function operateFormatter3(value, row, index) {
    num = Math.round(value * 100) / 100
    num = num.toFixed(2);
    return (formatNumber.new(num));
}
function rowStyle(row, index) {
    if (row.id==null) {
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
function sumaColumna(data) {
    field = this.field;
    let totalSum = data.reduce(function (sum, row) {
        return sum + (+row[field]);
    }, 0);
    return (formatNumber.new(totalSum.toFixed(2)));
}
function restornardatosSelect(res) {

    let cliente = new Array()
    let datos = new Array()
    $.each(res, function (index, value) {

        cliente.push(value.cliente)
    })
    cliente.sort();
    datos.push(cliente.unique());
    return (datos);
}
Array.prototype.unique = function (a) {
    return function () {
        return this.filter(a)
    }
}(function (a, b, c) {
    return c.indexOf(a, b + 1) < 0
});