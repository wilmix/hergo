var iniciofecha = moment().subtract(3, 'month').startOf('month')
var finfecha = moment().subtract(3, 'month').endOf('month')
$(document).ready(function () {
  $(".tiponumerico").inputmask({
    alias: "decimal",
    digits: 2,
    groupSeparator: ',',
    autoGroup: true,
    autoUnmask: true
  });

  var start = moment().subtract(3, 'month').startOf('month')
  var end = moment().subtract(3, 'month').endOf('month')

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
        'Ayer': [moment().subtract(1, 'days'), moment().subtract(1, 'days')],
        'Mes Actual': [moment().subtract(0, 'month').startOf('month'), moment().subtract(0, 'month').endOf('month')],
        "Hace un Mes": [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')],
        'Hace dos Meses': [moment().subtract(2, 'month').startOf('month'), moment().subtract(2, 'month').endOf('month')],
        'Hace tres Meses': [moment().subtract(3, 'month').startOf('month'), moment().subtract(3, 'month').endOf('month')],

      }
    }, cb);

    cb(start, end);

  });
  $('#fechapersonalizada').on('apply.daterangepicker', function (ev, picker) {
    tituloVentas();
    retornarLibroVentas();
    retornarDatosTotales();
  });
  tituloVentas();
  retornarLibroVentas();
  retornarDatosTotales();

})
$(document).on("change", "#almacen_filtro", function () {
  tituloVentas();
  retornarLibroVentas();
  retornarDatosTotales();
}) //para cambio filtro segun cada uno


function retornarLibroVentas() {
  ini = iniciofecha.format('YYYY-MM-DD')
  fin = finfecha.format('YYYY-MM-DD')
  alm = $("#almacen_filtro").val();
  agregarcargando();
  $.ajax({
    type: "POST",
    url: base_url('index.php/Reportes/mostrarLibroVentas'), //******controlador
    dataType: "json",
    data: {
      i: ini,
      f: fin,
      a: alm
    }, //**** variables para filtro
  }).done(function (res) {
    //console.log(res);
    quitarcargando();
    //console.log(ini);
    //console.log(fin);
    datosselect = restornardatosSelect(res);
    $("#tablaLibroVentas").bootstrapTable('destroy');
    $("#tablaLibroVentas").bootstrapTable({ ////********cambiar nombre tabla viata

      data: res,
      striped: true,
      pagination: true,
      pageSize: "100",
      search: true,
      showColumns: true,
      filter: true,
      stickyHeader: true,
      stickyHeaderOffsetY: '50px',
      showFooter: true,
      footerStyle: footerStyle,
      columns: [
        {
          field: '',
          title: 'E',
          visible: false,
          sortable: true,
        },
        {
          field: '',
          title: 'N°',
          sortable: true,
          formatter: contar
        },
        {
          field: 'fechaFac',
          title: 'Fecha',
          sortable: true,
          formatter: formato_fecha_corta
        },
        {
          field: 'nFactura',
          title: 'N° Factura',
          align: 'center',
          sortable: true,
        },
        {
          field: 'autorizacion',
          title: 'N° Autorización',
          sortable: true,
          filter: {
            type: "select",
            data: datosselect[0]
          }
        },
        {
          field: 'anulada',
          title: 'Estado',
          sortable: true,
          align: 'center',
          filter: {
            type: "select",
            data: ["Anulada", "Válida"]
          },
          formatter: estadoFactura
        },
        {
          field: 'documento',
          title: 'Nit Cliente',
          formatter: estadoAnuladoNIT,
          sortable: true
        },
        {
          field: 'nombreCliente',
          title: 'Nombre | Razón Social',
          sortable: true,
          formatter: estadoAnuladoCliente,
          filter: {
            type: "select",
            data: datosselect[1]
          },
          footerFormatter: "Total"
        },
        {
          field: 'sumaDetalle',
          title: 'Total Venta',
          sortable: true,
          align: 'right',
          formatter: estadoAnuladoTotal,
          footerFormatter: sumaColumna
        },
        {
          field: '',
          title: 'ICE',
          visible: false,
          sortable: true,
        },
        {
          field: '',
          title: 'EXP',
          visible: false,
          sortable: true,
        },
        {
          field: '',
          title: 'Taza Cero',
          visible: false,
          sortable: true,
        },
        {
          field: '',
          title: 'Subtotal',
          visible: false,
          sortable: true,
        },
        {
          field: '',
          title: 'Descuento',
          visible: false,
          sortable: true,
        },
        {
          field: '',
          title: ' Importe Base Debito Fiscal',
          visible: false,
          sortable: true
        },
        {
          field: 'debito',
          title: 'Debito Fiscal',
          sortable: true,
          align: 'right',
          formatter: estadoAnuladoDebito,
          footerFormatter: sumaColumna
        },
        {
          field: 'codigoControl',
          title: 'Codigo Control',
          sortable: true

        },
        {
          field: 'manual',
          title: 'Tipo',
          sortable: true,
          align: 'center',
          filter: {
            type: "select",
            data: ["MAN", "COM"]
          },
          formatter: tipoFactura
        }
      ]
    });
  }).fail(function (jqxhr, textStatus, error) {
    var err = textStatus + ", " + error;
    console.log("Request Failed: " + err);
  });
}

function contar(value, row, index) {
  return index + 1;
}

function retornarDatosTotales() {
  ini = iniciofecha.format('YYYY-MM-DD')
  fin = finfecha.format('YYYY-MM-DD')
  alm = $("#almacen_filtro").val();
  $.ajax({
    type: "POST",
    url: base_url('index.php/Reportes/mostrarLibroVentasTotales'), //******controlador
    dataType: "json",
    data: {
      i: ini,
      f: fin,
      a: alm
    }, //**** variables para filtro
  }).done(function (res) {
    /*console.log(res[0].titulo + ':0 ' + res[0].resultado);
    console.log(res[1].titulo + ':1 ' + res[1].resultado);
    console.log(res[2].titulo + ':2 ' + res[2].resultado);
    console.log(res[3].titulo + ':3 ' + res[3].resultado);
    console.log(res[4].titulo + ':4 ' + res[4].resultado);
    console.log(res[5].titulo + ':5 ' + res[5].resultado);
    console.log(res[6].titulo + ':6 ' + res[6].resultado);*/



    $('#fTotal').val(res[0].resultado);
    $('#fValidas').val(res[1].resultado);
    $('#fAnuladas').val(res[2].resultado);
    $('#fManuales').val(res[3].resultado);
    $('#fComputarizadas').val(res[4].resultado);
    $('#totalFacturas').val(res[5].resultado);
    $('#totalDebito').val(res[6].resultado);


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

function sumaColumna(data) {
  field = this.field;
  let totalSum = data.reduce(function (sum, row) {
    return sum + (+row[field]);
  }, 0);
  return (formatNumber.new(totalSum.toFixed(2)));
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

function tipoFactura(value, row, index) {
  $ret = ''
  if (row.manual == 1) {
    $ret = '<span class="label label-warning">MAN</span>';
  } else {
    $ret = '<span class="label label-default">COM</span>';
  }
  return ($ret);
}

function estadoAnuladoCliente(value, row, index) {
  $ret = ''
  if (row.anulada == 1) {
    $ret = '0';
  } else {
    $ret = row.nombreCliente;
  }
  return ($ret);
}

function estadoAnuladoNIT(value, row, index) {
  $ret = ''
  if (row.anulada == 1) {
    $ret = '0';
  } else {
    $ret = row.documento;
  }
  return ($ret);
}

function estadoAnuladoTotal(value, row, index) {
  $ret = ''
  if (row.anulada == 1) {
    $ret = '0';
  } else {
    $ret = row.sumaDetalle;
  }
  num = Math.round($ret * 100) / 100
  num = num.toFixed(2);
  return (formatNumber.new(num));
}

function estadoAnuladoDebito(value, row, index) {
  $ret = ''
  if (row.anulada == 1) {
    $ret = '0';
  } else {
    $ret = row.debito;
  }
  num = Math.round($ret * 100) / 100
  num = num.toFixed(2);
  return (formatNumber.new(num));
}

function estadoFactura(value, row, index) {
  $ret = ''
  if (row.anulada == 1) {
    $ret = '<span class="label label-danger ">Anulada</span>';
  } else {
    $ret = '<span class="label label-success">Válida</span>';
  }
  return ($ret);
}


function tituloVentas() {
  almText = $('#almacen_filtro').find(":selected").text();
  $('#tituloAlmacen').text(almText);
  $('#ragoFecha').text("DEL " + iniciofecha.format('DD/MM/YYYY') + "  AL  " + finfecha.format('DD/MM/YYYY'));
}

function restornardatosSelect(res) {
  var autor = new Array()
  var cliente = new Array()
  var datos = new Array()
  var destino = new Array()
  $.each(res, function (index, value) {
    autor.push(value.autorizacion)
    cliente.push(value.nombreCliente)
    destino.push(value.destino)
  })
  autor.sort();
  cliente.sort();
  datos.push(autor.unique());
  datos.push(cliente.unique());
  datos.push(destino.unique());
  return (datos);
}
Array.prototype.unique = function (a) {
  return function () {
    return this.filter(a)
  }
}(function (a, b, c) {
  return c.indexOf(a, b + 1) < 0
});