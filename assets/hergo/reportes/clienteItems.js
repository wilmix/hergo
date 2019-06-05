let iniciofecha = moment().subtract(0, 'month').startOf('month')
let finfecha = moment().subtract(0, 'month').endOf('month')

$(document).ready(function () {
    $('#export').click(function () {
        $('#tablaClienteItems').tableExport({
        type:'excel',
        fileName: 'Cliente Item',
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

    let start = moment().subtract(0, 'month').startOf('month')
    let end = moment().subtract(0, 'month').endOf('month')

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
        tituloReporte();
        retornarClienteItems();
    });
    tituloReporte();
    retornarClienteItems();
})
$(document).on("change", "#almacen_filtro", function () {
    tituloReporte();
    retornarClienteItems();
})
$(document).on("change", "#moneda", function () {
    tituloReporte();
    retornarClienteItems();
})
$(document).on("click", "#refresh", function () {
    tituloReporte();
    retornarClienteItems();
})



function retornarClienteItems() {
    let ini = iniciofecha.format('YYYY-MM-DD')
    let fin = finfecha.format('YYYY-MM-DD')
    let alm = $("#almacen_filtro").val()
    let mon = $("#moneda").val()
    agregarcargando();
    $.ajax({
        type: "POST",
        url: base_url('index.php/Reportes/showClienteItems'),
        dataType: "json",
        data: {
            i: ini,
            f: fin,
            a: alm,
        },
    }).done(function (res) {
        console.log(res);
        quitarcargando();
        
        for (let index = 0; index < res.length; index++) {
            if (res[index].ClienteFactura == null && res[index].codigo == null) {
                res[index].almacen = ''
                res[index].idArticulo = ''
                res[index].nFactura = ''
                res[index].idCliente = ''
                res[index].ClienteFactura =  'TOTAL'
                res[index].codigo = ''
                res[index].descrip = ''
                res[index].puni = ''
                res[index].cantidad = res[index].cantidad
                res[index].total = res[index].total

            }   else if (res[index].codigo == null) {
                res[index].almacen = ''
                res[index].idArticulo = ''
                res[index].nFactura = ''
                res[index].idCliente = ''
                res[index].ClienteFactura = res[index].ClienteFactura
                res[index].codigo = ''
                res[index].descrip = ''
                res[index].puni = ''
                res[index].cantidad = res[index].cantidad
                res[index].total = res[index].total

            }
        }
        console.log(res);
        //return false
        quitarcargando();
        datosselect = restornardatosSelect(res);
        console.log(datosselect);
        $("#tablaClienteItems").bootstrapTable('destroy');    
        $("#tablaClienteItems").bootstrapTable({ 
            data: res,
            striped: true,
            pagination: false,
            pageSize: "100",
            search: true,
            showColumns: true,
            filter: true,
            stickyHeader: true,
            stickyHeaderOffsetY: '50px',
            rowStyle:rowStyle,
            columns: [
                {
                    field: 'almacen',
                    title: 'Almacen',
                    visible: alm == '' ? true: false,
                    align: 'center',
                    searchable: false,
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
                    field: 'codigo',
                    title: 'Código',
                    sortable: true,
                },
                {
                    field: 'descrip',
                    title: 'Descripción',
                    align: 'left'
                },
               
              
                {
                    field: 'uni',
                    title: 'Unidad',
                    align:'center',
                    searchable: false,
                    //sortable: true,
                },
                {
                    field: 'cantidad',
                    title: 'Cantidad',
                    //sortable: true,
                    formatter:operateFormatter3,
                    align: 'right',
                    searchable: false,
                },
                {
                    field: 'puni',
                    title: 'P/U BOB PROMEDIO',
                    //sortable: true,
                    align: 'right',
                    formatter: precioUnitarioFormatter,
                    searchable: false,
                    visible: mon==1 ? false  : true
                },
                {
                    field: 'puDolares',
                    title: 'P/U $U$ PROMEDIO',
                    //sortable: true,
                    align: 'right',
                    formatter: precioUnitarioFormatter,
                    searchable: false,
                    visible: mon==1 ? true  : false

                },
                {
                    field: 'total',
                    title: 'Total BOB',
                    //sortable: true,
                    formatter: operateFormatter3,
                    searchable: false,
                    align: 'right',
                    visible: mon==1 ? false  : true
                },
                {
                    field: 'totalDolares',
                    title: 'Total $U$',
                    //sortable: true,
                    formatter: operateFormatter3,
                    searchable: false,
                    align: 'right',
                    visible: mon==1 ? true  : false
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

    var cliente = new Array()
    var datos = new Array()
    $.each(res, function (index, value) {

        cliente.push(value.ClienteFactura)
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

  
