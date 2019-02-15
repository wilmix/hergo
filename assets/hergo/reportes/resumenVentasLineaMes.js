var iniciofecha = moment().subtract(0, 'year').startOf('year')
var finfecha = moment().subtract(0, 'year').endOf('year')
$(document).ready(function () {
    $('#export').click(function () {
        $('#tablaResumenVentasLineaMes').tableExport({
        type:'excel',
        fileName: 'ResumenVentasLineaMes',
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
                'Mes Actual': [moment().subtract(0, 'month').startOf('month'), moment().subtract(0, 'month').endOf('month')],
                'Hace un Mes': [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
                'Hace dos Meses': [moment().subtract(2, 'month').startOf('month'), moment().subtract(2, 'month').endOf('month')],
                'Hace tres Meses': [moment().subtract(3, 'month').startOf('month'), moment().subtract(3, 'month').endOf('month')],
            }
        }, cb);

        cb(start, end);

    });
    $('#fechapersonalizada').on('apply.daterangepicker', function (ev, picker) {
        tituloReporte()
        retornarVentasLineaMes();
    });
    tituloReporte()
    retornarVentasLineaMes();
})
$(document).on("change", "#almacen_filtro", function () {
    tituloReporte()
    retornarVentasLineaMes();
})
$(document).on("click", "#refresh", function () {
    tituloReporte()
    retornarVentasLineaMes();
})



function retornarVentasLineaMes() 
{
    ini = iniciofecha.format('YYYY-MM-DD')
    fin = finfecha.format('YYYY-MM-DD')
    alm = $("#almacen_filtro").val();
    agregarcargando();
    $.ajax({
        type: "POST",
        url: base_url('index.php/Reportes/mostrarVentasLineaMes'),
        dataType: "json",
        data: {
            i: ini,
            f: fin,
            a: alm
        }, //**** variables para filtro
    }).done(function (res) {
        quitarcargando();
        $("#tablaResumenVentasLineaMes").bootstrapTable('destroy');
        $("#tablaResumenVentasLineaMes").bootstrapTable({ 

            data: res,
            striped: true,
            search: true,
            searchOnEnterKey: true,
            showColumns: true,
            showFooter: alm == '' ? false : true,
            footerStyle: footerStyle,
            filter: true,
            rowStyle:rowStyle,
            stickyHeader: true,
            stickyHeaderOffsetY: '50px',
            columns: [
                {
                    field: 'almacen',
                    title: 'Almacen',
                    align: 'center',
                    formatter: formAlm

                },
                {
                    field: 'Sigla',
                    title: 'Sigla',
                    sortable: false,
                    align: 'center',
                    visible:false

                },
                {
                    field: 'Linea',
                    title: 'Linea',
                    align: 'left',
                    sortable: true,
                },
                {
                    field: 'total',
                    title: 'Total BOB',
                    align: 'right',
                    sortable: true,
                    formatter: operateFormatter3,
                    footerFormatter: sumaColumna
                },
                {
                    field: 'dolares',
                    title: 'Total $U$',
                    align: 'right',
                    sortable: true,
                    formatter: operateFormatter3,
                    footerFormatter: sumaColumna
                },
                {
                    field: 'id',
                    title: 'id',
                    sortable: true,
                    align: 'center',
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
function operateFormatter3(value, row, index) {
    num = Math.round(value * 100) / 100
    num = num.toFixed(2);
    return (formatNumber.new(num));
}
function formAlm(value, row, index) {
    let alm = (row.id == null || row.Sigla == null) ? '': row.almacen
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

function sumaColumna(data) {
    field = this.field;
    let totalSum = data.reduce(function (sum, row) {
        return sum + (+row[field]);
    }, 0);
    return (formatNumber.new(totalSum.toFixed(2)));
}

function tituloReporte() {
    almText = $('#almacen_filtro').find(":selected").text();
    $('#tituloAlmacen').text(almText);
    $('#ragoFecha').text("DEL " + iniciofecha.format('DD/MM/YYYY') + "  AL  " + finfecha.format('DD/MM/YYYY'));
}