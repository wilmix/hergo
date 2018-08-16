
var iniciofecha = moment().subtract(5, 'year').startOf('year')
var finfecha = moment().subtract(0, 'year').endOf('year')
$(document).ready(function () {

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





$(document).on("click", "#kardex", function () {
    tituloReporte();
    retornarKardexCliente();
    
})
$(document).on("click", "#refresh", function () {
    tituloReporte();
    retornarKardexCliente();
})
$(document).on("change", "#clientes_filtro", function () {
    tituloReporte();
    retornarKardexCliente();
})

function retornarKardexCliente() {
    let ini = iniciofecha.format('YYYY-MM-DD')
    let fin = finfecha.format('YYYY-MM-DD')
    let almacen = $("#almacen_filtro").val()
    let cliente = $("#clientes_filtro").val()

    agregarcargando();
    $.ajax({
        type: "POST",
        url: base_url('index.php/Reportes/mostrarKardexIndividualCliente'),
        dataType: "json",
        data: {
            almacen: almacen,
            cliente: cliente,
            ini:ini,
            fin:fin,
        },
    }).done(function (res) {
        quitarcargando(); 
        if (res[0].fecha ===null) {
            res.shift()
        }
        $("#tablaKardex").bootstrapTable('destroy');    
        $("#tablaKardex").bootstrapTable({ 
            data: res,
            striped: true,
            filter: true,
            stickyHeader: true,
            stickyHeaderOffsetY: '50px',
            showFooter: true,
            footerStyle: footerStyle,
            columns: [
                {
                    field: 'idCliente',
                    title: 'Almacen',
                    align: 'center',
                    visible: false
                },
                {
                    field: 'nombreCliente',
                    title: 'Cliente',
                    align: 'center',
                    visible: false
                },
                {
                    field: 'fecha',
                    title: 'Fecha',
                    align: 'center',
                    formatter: formato_fecha_corta

                },
                {
                    field: 'numDocumento',
                    title: 'N° Doc.',
                    align: 'left'
                },
                {
                    field: 'almacen',
                    title: 'Alm.',
                    align: 'center'
                },
                {
                    field: 'detalle',
                    title: 'Detalle',
                    align: 'left'
                },
        
                {
                    field: 'saldoNE',
                    title: 'NotaEntrega',
                    align: 'right',
                    formatter: operateFormatter3,
                    footerFormatter: sumaColumna
                },
                {
                    field: 'saldoTotalFactura',
                    title: 'Factura',
                    align: 'right',
                    formatter: operateFormatter3,
                    footerFormatter: sumaColumna
                },
                {
                    field: 'saldoTotalPago',
                    title: 'Pago',
                    align: 'right',
                    formatter: operateFormatter3,
                    footerFormatter: sumaColumna

                },
                {
                    field: 'total',
                    title: 'Total',
                    align: 'right',
                    formatter: operateFormatter3,
                    //footerFormatter: sumaColumna
                },
            ]
          });
    }).fail(function (jqxhr, textStatus, error) {
        var err = textStatus + ", " + error;
        console.log("Request Failed: " + err);
    });
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
function sumaColumna(data) {
    field = this.field;
    let totalSum = data.reduce(function (sum, row) {
      return sum + (+row[field]);
    }, 0);
    return (formatNumber.new(totalSum.toFixed(2)));
  }
