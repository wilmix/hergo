var iniciofecha = moment().subtract(1, 'month').startOf('month')
var finfecha = moment().subtract(1, 'month').endOf('month')
$(document).ready(function () {
  $(".tiponumerico").inputmask({
    alias: "decimal",
    digits: 2,
    groupSeparator: ',',
    autoGroup: true,
    autoUnmask: true
  });

  $('#export').click(function () {
    $('#tablaTMventas').tableExport({
      type:'excel',
      fileName: 'VentasTM',
      numbers: {output : false}
    })
  });

  var start = moment().subtract(1, 'month').startOf('month')
  var end = moment().subtract(1, 'month').endOf('month')

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
    tituloReporte();
    returnVentasTM();
  });
  tituloReporte();
  returnVentasTM();

})
$(document).on("click", "#refresh", function () {
    tituloReporte() 
    returnVentasTM();
})
$(document).on("change", "#almacen_filtro", function () {
  tituloReporte();
  returnVentasTM();
})

function tituloReporte() {
    almText = $('#almacen_filtro').find(":selected").text();
    $('#tituloAlmacen').text(almText);
    $('#ragoFecha').text("DEL " + iniciofecha.format('DD/MM/YYYY') + "  AL  " + finfecha.format('DD/MM/YYYY'));
}

function returnVentasTM() {
    tituloReporte();
    ini = iniciofecha.format('YYYY-MM-DD')
    fin = finfecha.format('YYYY-MM-DD')
    alm = $("#almacen_filtro").val();
    agregarcargando();
    $.ajax({
      type: "POST",
      url: base_url('index.php/Reportes/showVentasTM'),
      dataType: "json",
      data: {
        i: ini,
        f: fin,
        a: alm
      }, //**** variables para filtro
    }).done(function (res) {
      quitarcargando();
      console.log(res);
      //datosselect = restornardatosSelect(res);
      $("#tablaTMventas").bootstrapTable('destroy');
      $("#tablaTMventas").bootstrapTable({
  
        data: res,
        striped: true,
        search: true,
        showColumns: true,
        filter: true,
        stickyHeader: true,
        stickyHeaderOffsetY: '50px',
        //showFooter: true,
        //footerStyle: footerStyle,
        columns: [
          {
            field: 'codigo',
            title: 'Código',
            sortable: true,
          },
          {
            field: 'descripcion',
            title: 'Descripción',
            sortable: true,
          },
          {
            field: 'nit',
            title: 'NIT',
            sortable: true,
          },
          {
            field: 'razon',
            title: 'Razón Social',
            sortable: true,

          },
          {
            field: 'cantidad',
            title: 'Cantidad',
            sortable: true,
          },
          {
            field: 'unidad',
            title: 'Unidad',
            sortable: true,
          },
          {
            field: 'pu',
            title: 'P/U',
            sortable: true,
            align: 'right',
            formatter: operateFormatter3,
          },
          {
            field: 'total',
            title: 'Total',
            sortable: true,
            align: 'right',
            formatter: operateFormatter3,
          },
          {
            field: 'moneda',
            title: 'Moneda',
          },
          {
            field: 'fecha',
            title: 'Fecha',
            width:'100px',
            sortable: true,
            align: 'center'
          },
          {
            field: '',
            title: 'Ubigeo',
            //visible: false
          },
          {
            field: 'numDoc',
            title: 'N° Documento',
            align: 'center',
            sortable: true,
          },
          {
            field: 'tipo',
            title: 'Tipo',
            //visible: false
          },
          {
            field: 'nodef',
            title: 'Mercado',
            //visible: false
          },
          {
            field: 'nodef',
            title: 'Distribuidor',
            //visible: false
          },
          {
            field: 'nodef',
            title: 'Zona',
            //visible: false
          },
          {
            field: 'ciudad',
            title: 'Ciudad',
          },
          {
            field: 'nodef',
            title: 'Distrito',
            //visible: false
          },
          {
            field: '',
            title: 'Bonificación',
            //visible: false
          },
          {
            field: 'vendedor',
            title: 'Vendedor',
            visible: true
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
  