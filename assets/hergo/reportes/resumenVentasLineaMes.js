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
                'Hace un año': [moment().subtract(1, 'year').startOf('year'), moment().subtract(1, 'year').endOf('year')],
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
$(document).on("change", "#moneda", function () {
    tituloReporte()
    retornarVentasLineaMes();
})




function retornarVentasLineaMes() 
{
    ini = iniciofecha.format('YYYY-MM-DD')
    fin = finfecha.format('YYYY-MM-DD')
    alm = $("#almacen_filtro").val()
    mon = $("#moneda").val()
    console.log(mon);

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
            filter: true,
            rowStyle:rowStyle,
            stickyHeader: true,
            stickyHeaderOffsetY: '50px',
            columns: [
                {
                    field: 'Sigla',
                    title: 'Sigla',
                    align: 'center',
                    visible:true

                },
                {
                    field: 'Linea',
                    title: 'Linea',
                    align: 'left',
                    formatter: formVacio,
                    //sortable: true,
                    //formatter: formVacio,
                },
                
                {
                    field: mon == 1 ? 'eneD' : 'ene',
                    title: 'Enero',
                    align: 'right',
                    //sortable: true,
                    formatter: operateFormatter3,
                    
                },
                {
                    field: mon == 1 ? 'febD' : 'feb',
                    title: 'Febrero',
                    align: 'right',
                    //sortable: true,
                    formatter: operateFormatter3,
                    
                },
                {
                    field: mon == 1 ? 'marD' : 'mar',
                    title: 'Marzo',
                    align: 'right',
                    //sortable: true,
                    formatter: operateFormatter3,
                    
                },
                {
                    field: mon == 1 ? 'abrD' : 'abr',
                    title: 'Abril',
                    align: 'right',
                    //sortable: true,
                    formatter: operateFormatter3,
                    
                },
                {
                    field: mon == 1 ? 'mayD' : 'may',
                    title: 'Mayo',
                    align: 'right',
                    //sortable: true,
                    formatter: operateFormatter3,
                    
                },
                {
                    field: mon == 1 ? 'junD' : 'jun',
                    title: 'Junio',
                    align: 'right',
                    //sortable: true,
                    formatter: operateFormatter3,
                    
                },
                {
                    field: mon == 1 ? 'julD' : 'jul',
                    title: 'Julio',
                    align: 'right',
                    //sortable: true,
                    formatter: operateFormatter3,
                    
                },
                {
                    field: mon == 1 ? 'agoD' : 'ago',
                    title: 'Agosto',
                    align: 'right',
                    //sortable: true,
                    formatter: operateFormatter3,
                    
                },
                {
                    field: mon == 1 ? 'sepD' : 'sep',
                    title: 'Septiembre',
                    align: 'right',
                    //sortable: true,
                    formatter: operateFormatter3,
                    
                },
                {
                    field: mon == 1 ? 'ocbD' : 'ocb',
                    title: 'Octubre',
                    align: 'right',
                    //sortable: true,
                    formatter: operateFormatter3,
                    
                },
                {
                    field: mon == 1 ? 'novD' : 'nov',
                    title: 'Noviembre',
                    align: 'right',
                    //sortable: true,
                    formatter: operateFormatter3,
                    
                },
                {
                    field: mon == 1 ? 'dicD' : 'dic',
                    title: 'Diciembre',
                    align: 'right',
                    //sortable: true,
                    formatter: operateFormatter3,
                    
                },
                {
                    field: mon == 1 ? 'totalD' : 'total',
                    title: 'Total',
                    align: 'right',
                    //sortable: true,
                    formatter: operateFormatter3,
                    
                },

            ]
        });
    }).fail(function (jqxhr, textStatus, error) {
        var err = textStatus + ", " + error;
        console.log("Request Failed: " + err);
    });
}
function rowStyle(row, index) {
    if (row.Sigla==null) {
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

function formVacio(value, row, index) {
    let ret = (row.Sigla == null ) ? 'TOTAL': row.Linea
    return (ret);
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
    console.log(totalSum);
    return (formatNumber.new(totalSum.toFixed(2)));
}

function tituloReporte() {
    let almText = $('#almacen_filtro').find(":selected").text();
    let mon = $('#moneda').val();
    mon = mon == 1 ? 'DOLARES' : 'BOLIVIANOS'
    $('#tituloAlmacen').text(almText);
    $('#ragoFecha').text("DEL " + iniciofecha.format('DD/MM/YYYY') + "  AL  " + finfecha.format('DD/MM/YYYY'));
    $('#monedaTitulo').text(mon);
}