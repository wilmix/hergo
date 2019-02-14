var iniciofecha = moment().subtract(10, 'year').startOf('year')
var finfecha = moment().subtract(0, 'year').endOf('year')
$(document).ready(function () {
    $('#export').click(function () {
        let ppp = $("#select2-4rkh-container").val()
        console.log(ppp);
        $('#tablaFacturasPendientes').tableExport({
        type:'excel',
        fileName: 'FacturasPendientesPago',
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

    var start = moment().subtract(10, 'year').startOf('year')
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
                'Todo': [moment().subtract(10, 'year').startOf('year'), moment().subtract(0, 'year').endOf('year')],
            }
        }, cb);

        cb(start, end);

    });
    $('#fechapersonalizada').on('apply.daterangepicker', function (ev, picker) {
        tituloReporte()
        retornarFacturasPendientes();
    });
    tituloReporte()
    retornarFacturasPendientes();
})
$(document).on("change", "#almacen_filtro", function () {
    retornarFacturasPendientes();
}) //para cambio filtro segun cada uno

/*$(document).ready(function() {
    tituloReporte()
    retornarFacturasPendientes();
});*/
$(document).on("change", "#almacen_filtro", function () {
    tituloReporte()
    retornarFacturasPendientes();
}) 
$(document).on("click", "#pendientes", function () {
    tituloReporte();
    retornarFacturasPendientes();
})

function tituloReporte() {
    almText = $('#almacen_filtro').find(":selected").text();
    $('#tituloReporte').text(almText);
}
$(document).on("click", "#excel", function () {
    let alm = $("#almacen_filtro").val()
    let mon = $("#moneda").val()
    let tc = (mon == 1) ?  glob_tipoCambio : 'BOB'
    alm = (alm == '') ?  'NN' : alm
    let excel = base_url("ReportesExcel/facturasPendientesPago/"+alm);
    console.log(excel);
    location.href = (excel);
})
function retornarFacturasPendientes()
{
    ini = iniciofecha.format('YYYY-MM-DD')
    fin = finfecha.format('YYYY-MM-DD')
    almacen = $("#almacen_filtro").val();
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
        for (let index = 0; index < res.length; index++) {
            if (res[index].id == null && res[index].cliente == null) {
                res[index].cliente = `TOTAL GENERAL`
                res[index].lote = ''
                res[index].nFactura = ''
                res[index].fechaFac = ''
                res[index].saldo = (Number(res[index].total) - Number(res[index].montoPagado))
            } else if (res[index].id == null) {
                res[index].lote = ''
                res[index].nFactura = ''
                res[index].fechaFac = ''
                res[index].saldo = (Number(res[index].total) - Number(res[index].montoPagado))

            } else  {

                res[index].saldo = (Number(res[index].total) - Number(res[index].montoPagado))

            }
        }
        //console.log(res);
        datosselect = restornardatosSelect(res);
        $("#tablaFacturasPendientes").bootstrapTable('destroy');
        $("#tablaFacturasPendientes").bootstrapTable({ 
            data: res,
            striped: true,
            searchOnEnterKey: true,
            showColumns: true,
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
                    sortable: true,
                    visible: almacen==''?true:false
                },
                {
                    field: 'lote',
                    title: 'Lote',
                    width:'50px',
                    sortable: true,
                    align: 'center'
                },
                {
                    field: 'nFactura',
                    title: 'N° Factura',
                    sortable: true,
                    width:'80px',
                    align: 'center',
                },
                {
                    field: 'fechaFac',
                    title: 'Fecha',
                    width:'100px',
                    sortable: true,
                    align: 'center',
                    formatter: formato_fecha_corta_sub
                },
                {
                    field: 'cliente',
                    title: 'Cliente',
                    sortable: true,
                     //visible:false,
                    filter: {
                        type: "select",
                        data: datosselect[0]
                    }
                },
                {
                    field: 'total',
                    title: 'Crédito',
                    sortable: true,
                    align: 'right',
                    width:'100px',
                    formatter: operateFormatter3,
                },
                {
                    field: 'montoPagado',
                    title: 'Abono',
                    sortable: true,
                    align: 'right',
                    width:'100px',
                    formatter: operateFormatter3,
                },
                {
                    field: 'saldo',
                    title: 'Saldo',
                    sortable: true,
                    align: 'right',
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
        var err = textStatus + ", " + error;
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

    var cliente = new Array()
    var datos = new Array()
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