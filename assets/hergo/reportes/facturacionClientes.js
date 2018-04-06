var iniciofecha = moment().subtract(0, 'year').startOf('year')
var finfecha = moment().subtract(0, 'year').endOf('year')
$(document).ready(function () {
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
    });
    retornarfacturacionClientes();
})
$(document).on("change", "#almacen_filtro", function () {
    retornarfacturacionClientes();
}) //para cambio filtro segun cada uno



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
        console.log(res);
        //console.log(alm);
        //datosselect= restornardatosSelect(res);
        $("#tablaFacturacionClientes").bootstrapTable('destroy');
        $("#tablaFacturacionClientes").bootstrapTable({ ////********cambiar nombre tabla viata

            data: res,
            striped: true,
            pagination: true,
            pageSize: "100",
            search: true,
            searchOnEnterKey: true,
            showColumns: true,
            filter: true,
            showExport: true,
            exportTypes: ['xlsx'],
            exportDataType: 'basic',
            /*exportOptions:{
                            bookType:"xlsx",
                            type:'excel',
                            fileName: 'Notas',
                            worksheetName: "Notas"
                          },*/
            //groupBy:true,
            //groupByField:'nombreCliente',
            stickyHeader: true,
            stickyHeaderOffsetY: '50px',
            columns: [{
                    field: 'nombreCliente',
                    title: 'Cliente',
                    sortable: true,
                    filter: {
                        type: "input"
                    }
                },
                {
                    field: 'total',
                    title: 'Total ',
                    align: 'right',
                    formatter: operateFormatter3
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
    $('#tituloAlmacen').text(almText);
    $('#ragoFecha').text("DEL " + iniciofecha.format('DD/MM/YYYY') + "  AL  " + finfecha.format('DD/MM/YYYY'));
}
function operateFormatter3(value, row, index) {
    num = Math.round(value * 100) / 100
    num = num.toFixed(2);
    return (formatNumber.new(num));
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