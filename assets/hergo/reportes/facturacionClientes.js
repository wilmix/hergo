var iniciofecha = moment().subtract(0, 'year').startOf('year')
var finfecha = moment().subtract(0, 'year').endOf('year')
$(document).ready(function () {
    $('#export').click(function () {
        $('#tablaFacturacionClientes').tableExport({
        type:'excel',
        fileName: 'Facturacion Clientes',
        numbers: {output : false}
        })
      });

    tituloReporte();
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
        retornarfacturacionClientes();
        tituloReporte();
    });
    retornarfacturacionClientes();
    tituloReporte();
})
$(document).on("change", "#almacen_filtro", function () {
    retornarfacturacionClientes();
    tituloReporte();
})
$(document).on("change", "#moneda", function () {
    retornarfacturacionClientes();
    tituloReporte();
})


function retornarfacturacionClientes() //*******************************
{
    ini = iniciofecha.format('YYYY-MM-DD')
    fin = finfecha.format('YYYY-MM-DD')
    alm = $("#almacen_filtro").val();
    agregarcargando();
    $.ajax({
        type: "POST",
        url: base_url('index.php/Reportes/mostrarFacturacionClientes'), //******controlador
        dataType: "json",
        data: {
            i: ini,
            f: fin,
            a: alm
        }, //**** variables para filtro
    }).done(function (res) {
        quitarcargando();
        $("#tablaFacturacionClientes").bootstrapTable('destroy');
        $("#tablaFacturacionClientes").bootstrapTable({ 
            data: res,
            striped: true,
            search: true,
            //searchOnEnterKey: true,
            showColumns: true,
            filter: true,
            stickyHeader: true,
            stickyHeaderOffsetY: '50px',
            rowStyle:rowStyle,
            showFooter: (alm == '')?false:true,
            filter: true,
            footerStyle: footerStyle,
            columns: [
                {
                    field: 'almacen',
                    title: 'Almacen',
                    formatter: formatAlm,
                    visible: alm == '' ? true : false
                },
                {
                    field: 'nombreCliente',
                    title: 'Cliente',
                    sortable: true,
                },
                {
                    field: 'total',
                    title: 'Total BOB',
                    align: 'right',
                    formatter: operateFormatter3,
                    footerFormatter: sumaColumna,
                },
                {
                    field: 'totalDolares',
                    title: 'Total $U$',
                    align: 'right',
                    formatter: operateFormatter3,
                    footerFormatter: sumaColumna,
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
    console.log(almText);
    let ini = iniciofecha.format('DD/MM/YYYY')
    let fin = finfecha.format('DD/MM/YYYY')
    $('#tituloAlmacen').text(almText);

    //$('#ragoFecha').text("DEL " + iniciofecha.format('DD/MM/YYYY') + "  AL  " + finfecha.format('DD/MM/YYYY'));
    $('#ragoFecha').text("DEL " + ini + "  AL  " + fin);
}
function operateFormatter3(value, row, index) {
    num = Math.round(value * 100) / 100
    num = num.toFixed(2);
    return (formatNumber.new(num));
}
function formatAlm(value, row, index) {
    let alm = row.id == null ? '' : row.almacen
    return (alm);
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

    var alm = new Array()
    var cliente = new Array()
    var responsable = new Array()
    var datos = new Array()
    $.each(res, function (index, value) {

        alm.push(value.almacen)
        cliente.push(value.nombreCliente)
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