
let iniciofecha = moment().subtract(0, 'year').startOf('year')
let finfecha = moment().subtract(0, 'year').endOf('year')
$(document).ready(function () {
    tituloReporte()
     $('#export').click(function () {
        nameCliente = ($("#nameClient").val())
        $('#tablaKardex').tableExport({
        type:'excel',
        fileName: 'KARDEX INDIVIDUAL ' + nameCliente,
        numbers: {output : false}
        })
    });
    let start = iniciofecha
    let end = finfecha

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
        retornarKardexCliente();
    }); 
})


$(document).on("click", "#pdf", function () {
    let ini = iniciofecha.format('YYYY-MM-DD')
    let fin = finfecha.format('YYYY-MM-DD')
    let almacen = $("#almacen_filtro").val()
    let cliente = $("#idCliente").val()
    let mon = $("#moneda").val()
    let imprimir = base_url("pdf/ReportKardexCliente/index/") + cliente + '/' +  almacen + '/' + ini + '/' + fin +  '/' + mon;
    console.log(imprimir);
    window.open(imprimir);
})


$(document).on("click", "#kardex", function () {
    retornarKardexCliente();
})
$(document).on("change", "#moneda", function () {
    retornarKardexCliente();
})
$(document).on("click", "#refresh", function () {
    retornarKardexCliente();
})

function retornarKardexCliente() {
    tituloReporte()
    let ini = iniciofecha.format('YYYY-MM-DD')
    let fin = finfecha.format('YYYY-MM-DD')
    let almacen = $("#almacen_filtro").val()
    let cliente = $("#idCliente").val()
    let mon = $("#moneda").val()
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
            mon:mon
        },
    }).done(function (res) {
        quitarcargando(); 
        if (res[0].fecha ==='') {
            res.shift()
        }
        res.forEach(e => {
            if (e.numDocumento=='-') {
                e.tipo = '-'
            } else if (e.saldoNE>0) {
                e.tipo = 'NE'
            } else if (e.saldoTotalFactura>0) {
                e.tipo = 'FAC'
            } else if (e.saldoTotalPago>0) {
                e.tipo = 'REC'
            }

        });
        $("#tablaKardex").bootstrapTable('destroy');    
        $("#tablaKardex").bootstrapTable({ 
            data: res,
            striped: true,
            filter: true,
            stickyHeader: true,
            showColumns: true,
            stickyHeaderOffsetY: '50px',
            showFooter: true,
            footerStyle: footerStyle,
            columns: [
                {
                    field: 'idCliente',
                    title: 'id',
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
                    width:'100px',
                    formatter: formato_fecha_corta

                },
                {
                    field: 'tipo',
                    title: 'Tipo.',
                    width:'80px',
                    align: 'left',
                    
                },
                {
                    field: 'numDocumento',
                    title: 'N° Doc.',
                    width:'80px',
                    align: 'left',
                    
                },
                {
                    field: 'almacen',
                    title: 'Alm.',
                    align: 'center',
                    width:'150px',
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
                    width:'100px',
                    formatter: operateFormatter3,
                    footerFormatter: sumaColumna
                },
                {
                    field: 'saldoTotalFactura',
                    title: 'Factura',
                    align: 'right',
                    width:'100px',
                    formatter: operateFormatter3,
                    footerFormatter: sumaColumna
                },
                {
                    field: 'saldoTotalPago',
                    title: 'Pago',
                    align: 'right',
                    width:'100px',
                    formatter: operateFormatter3,
                    footerFormatter: sumaColumna

                },
                {
                    field: 'total',
                    title: 'Total',
                    align: 'right',
                    formatter: operateFormatter3,
                    width:'100px',
                    //footerFormatter: sumaColumna
                },
            ]
          });
    }).fail(function (jqxhr, textStatus, error) {
        let err = textStatus + ", " + error;
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
function tituloReporte(nomCliente) {
    let almText = $('#almacen_filtro').find(":selected").text()
    let mon = $("#moneda").val()
    mon = mon == 0 ? 'BOLIVIANOS' : 'DOLARES'
    $('#ragoFecha').text("DEL " + iniciofecha.format('DD/MM/YYYY') + "  AL  " + finfecha.format('DD/MM/YYYY'))
    $('#tituloReporte').text(almText)
    $('#nombreCliente').text(nomCliente)
    $('#titleMoneda').text(mon)

}
function sumaColumna(data) {
    field = this.field;
    let totalSum = data.reduce(function (sum, row) {
      return sum + (+row[field]);
    }, 0);
    return (formatNumber.new(totalSum.toFixed(2)));
  }
/*******************CLIENTE*****************/
$(function () {
    $("#cliente_egreso").autocomplete({
            minLength: 2,
            autoFocus: true,
            source: function (request, response) {
                $("#cargandocliente").show(150)
                $("#clientecorrecto").html('<i class="fa fa-times" style="color:#bf0707" aria-hidden="true"></i>')
                glob_guardar_cliente = false;
                $.ajax({
                    url: base_url("index.php/Egresos/retornarClientes"),
                    dataType: "json",
                    data: {
                        b: request.term.trim()
                    },
                    success: function (data) {
                        response(data);
                        $("#cargandocliente").hide(150)
                    }
                });

            },
            select: function (event, ui) {
                tituloReporte(ui.item.nombreCliente)
                $("#clientecorrecto").html('<i class="fa fa-check" style="color:#07bf52" aria-hidden="true"></i>');
                $("#cliente_egreso").val(ui.item.nombreCliente + " - " + ui.item.documento);
                $("#idCliente").val(ui.item.idCliente);
                $("#nameClient").val(ui.item.nombreCliente);
                glob_guardar_cliente = true;
                retornarKardexCliente()
                return false;
            }
        })
        .autocomplete("instance")._renderItem = function (ul, item) {

            return $("<li>")
                .append("<a><div>" + item.nombreCliente + " </div><div style='color:#615f5f; font-size:10px'>" + item.documento + "</div></a>")
                .appendTo(ul);
        };

});