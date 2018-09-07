
var iniciofecha = moment().subtract(5, 'year').startOf('year')
var finfecha = moment().subtract(0, 'year').endOf('year')
$(document).ready(function () {
    $('#articulos_filtro').select2({
        theme: "classic",
    });
    retornarVentasClienteItems()

    let start = moment().subtract(0, 'year').startOf('year')
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
        'Hace tres Años': [moment().subtract(3, 'year').startOf('year'), moment().subtract(3, 'year').endOf('year')],
      }
    }, cb);

    cb(start, end);

  });
  $('#fechapersonalizada').on('apply.daterangepicker', function (ev, picker) {
    tituloReporte()
   });
  tituloReporte()
})

$(document).on("click", "#refresh", function () {
    tituloReporte();
    retornarVentasClienteItems();
})
$(document).on("change", "#articulos_filtro", function () {
    tituloReporte();
    retornarVentasClienteItems();
})
$(document).on("change", "#almacen_filtro", function () {
    tituloReporte();
    retornarVentasClienteItems();
})

function retornarVentasClienteItems() {
    let ini = iniciofecha.format('YYYY-MM-DD')
    let fin = finfecha.format('YYYY-MM-DD')
    let alm = $("#almacen_filtro").val()
    let item = $("#articulos_filtro").val()
    agregarcargando();
    $.ajax({
        type: "POST",
        url: base_url('index.php/Reportes/mostrarVentasClientesItems'),
        dataType: "json",
        data: {
            alm: alm,
            item: item,
            ini:ini,
            fin:fin,
        },
    }).done(function (res) {
        for (let index = 0; index < res.length; index++) {
            if (res[index].nFactura == null && res[index].codigo == null) {
                res[index].nombreCliente = `TOTAL GENERAL:`
                res[index].descripcion = ''
                res[index].fechaFac = ' '
                res[index].almacen = ''
                res[index].vendedor = ''
                res[index].pUni = ''
                res[index].facCant = ''
            } else if (res[index].nFactura == null) {
                res[index].descripcion = ''
                res[index].fechaFac = ' '
                res[index].almacen = ''
                res[index].nombreCliente = `TOTAL ${res[index].codigo}:`
                res[index].codigo = ''
                res[index].vendedor = ''
            }
        }
        console.log(res);
        quitarcargando(); 
        $("#tablaVentasClientesItems").bootstrapTable('destroy');    
        $("#tablaVentasClientesItems").bootstrapTable({ 
            data: res,
            striped: true,
            filter: true,
            stickyHeader: true,
            stickyHeaderOffsetY: '50px',
            rowStyle:rowStyle,
            showColumns: true,
            columns: [
                {
                    field: 'codigo',
                    title: 'Codigo',
                    align: 'center',
                },
                {
                    field: 'descripcion',
                    title: 'Descripcion',
                    align: 'left',
                },
                {
                    field: 'almacen',
                    title: 'Alm.',
                    width:'50px',
                    align: 'center'
                },
                {
                    field: 'fechaFac',
                    title: 'Fecha',
                    align: 'center',
                    width:'100px',

                },
                {
                    field: 'nFactura',
                    title: 'N° Fac.',
                    width:'80px',
                    align: 'center'
                },
                
                {
                    field: 'documento',
                    title: 'NIT',
                    align: 'left',
                    visible: false
                },
                {
                    field: 'nombreCliente',
                    title: 'Cliente',
                    align: 'left'
                },
        
                {
                    field: 'pUni',
                    title: 'P/U',
                    align: 'right',
                    width:'100px',
                    formatter: operateFormatter3,
                    //footerFormatter: sumaColumna
                },
                {
                    field: 'facCant',
                    title: 'Cant.',
                    align: 'right',
                    width:'100px',
                    formatter: operateFormatter3,
                    //footerFormatter: sumaColumna
                },
                {
                    field: 'total',
                    title: 'Total',
                    align: 'right',
                    width:'100px',
                    formatter: operateFormatter3,
                    //footerFormatter: sumaColumna
                },
                {
                    field: 'vendedor',
                    title: 'Vendedor',
                    align: 'left',
                },
            ]
          })
    }).fail(function (jqxhr, textStatus, error) {
        var err = textStatus + ", " + error;
        console.log("Request Failed: " + err);
    })
}
function rowStyle(row, index) {
    if (row.nFactura==null) {
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
function footerStyle(value, row, index) {
    return {
        css: {
            "font-weight": "normal",
            "border-top": "3px solid white",
            "border-bottom": "3px solid white",
            "text-align": "right",
            "padding": "15px",
            "background-color": "#3c8dbc",
            "color": "white"
        }
    };
}
function operateFormatter3(value, row, index) {
    num = Math.round(value * 100) / 100
    num = num.toFixed(2);
    return (formatNumber.new(num));
}
function tituloReporte() {
    almText = $('#almacen_filtro').find(":selected").text();
    nomCliente = $('#clientes_filtro').find(':selected').text();
    $('#tituloReporte').text(almText);
    $('#nombreCliente').text(nomCliente);
}

