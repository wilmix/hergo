  $(document).ready(function(){
  
  
    getVentasHoy()
    getIngresosHoy()
    getVentas()
    getInfoHoy()
    getNotaEntregaHoy()
    getVentaCajaHoy()
    showNegatives()
    //newTable()
    //getCantidadHoy()
})

function numberCoin (data, type, row){
  num = Math.round(data * 100) / 100
  num = num.toFixed(2);
  return (formatNumber.new(num));
}

let today = moment().subtract(0, 'month').startOf('day').format('YYYY-MM-DD')
let yearAgo = moment().subtract(12, 'month').startOf('month').format('YYYY-MM-DD')

function ventasChart(meses, alm1, alm2, alm3,totalMes) {
    var ctx = document.getElementById('ventas').getContext('2d');
    var chart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: meses,
            datasets: [{
                label: "LA PAZ",
                borderColor: "#3e95cd",
                data: alm1,
                fill: false,
                pointStyle: 'rectRounded',
                lineTension: 0,
            },
            {
                label: "POTOSI",
                borderColor: "#ff0000",
                data: alm2, 
                fill: false,
                pointStyle: 'rectRounded',
                lineTension: 0,
            },
            {
              label: "SANTA CRUZ",
              borderColor: "#009f00",
              data: alm3,
              fill: false,
              pointStyle: 'rectRounded',
              lineTension: 0,
            },
            {
              label: "TOTAL",
              borderColor: "#000000",
              data: totalMes,
              fill: true,
              pointStyle: 'rectRounded',
              lineTension: 0,

            }
        ]},
        options: { 
          scales: { 
            yAxes: [{ 
              ticks: { 
                beginAtZero: true,
                callback: function (value) { 
                  return formatNumber.new(value) 
                } 
              } 
            }] 
          },
          tooltips:{
            enabled: true,
            callbacks: {
              label: function(tooltipItem, data) {
                  var label = data.datasets[tooltipItem.datasetIndex].label || '';
                  if (label) {
                      label += ': ';
                  }
                  label += formatNumber.new(tooltipItem.yLabel);
                  return label;
              }
            },
          },
        },
    });
}

function getVentas() {
    today1 = moment().subtract(-1, 'day').startOf('day').format('YYYY-MM-DD')
    ini = yearAgo
    fin = today1
    $.ajax({
      type: "POST",
      url: base_url('index.php/Principal/ventasGestion'),
      dataType: "json",
      data: {
        i: ini,
        f: fin,
      }, 
    }).done(function (res) {
      let montoLP = res.map (ventas => ventas.montoLP)
      let montoPTS = res.map (ventas => ventas.montoPTS)
      let montoSCZ = res.map (ventas => ventas.montoSCZ)
      let montoTotal = res.map (ventas => ventas.totalMes)
      let tiempo = res.map( ventas => ventas.mes + " " + ventas.gestion)
      ventasChart(tiempo, montoLP, montoPTS, montoSCZ, montoTotal)
    }).fail(function (jqxhr, textStatus, error) {
      var err = textStatus + ", " + error;
      console.log("Request Failed: " + err);
    });
}


function getIngresosHoy() {
  ini = today
  $.ajax({
    type: "POST",
    url: base_url('index.php/Principal/ingresosHoy'),
    dataType: "json",
    data: {
      i: ini,
    }, 
  }).done(function (res) {
    //console.log(res);
    if (res =='') {
      //console.log('vacioIngresosHoy');
      $("#ingresosHoy").html('0 '+"<small> Bs</small>")
    } else {
      let ingresosHoyLP = res[0].lp
      console.log(ingresosHoyLP);
      $("#ingresosHoy").html(formatNumber.new(ingresosHoyLP)+"<small> Bs</small>")
    }
  }).fail(function (jqxhr, textStatus, error) {
    var err = textStatus + ", " + error;
    console.log("Request Failed: " + err);
  });
}

function getVentasHoy() {
  ini = today
  $.ajax({
    type: "POST",
    url: base_url('index.php/Principal/ventasHoy'),
    dataType: "json",
    data: {
      i: ini,
    }, 
  }).done(function (res) {
    //console.log(res);
    if (res[0].ventasHoy == null) {
      $("#ventasHoy").html('0 '+"<small> Bs</small>")
      $("#cantidad").html('0')
    } else {
      let hoy = res[0].ventasHoy
      let cantidad = res[0].cantidadHoy
      $("#ventasHoy").html(formatNumber.new(Number(hoy).toFixed(2))+"<small> Bs</small>")
      $("#cantidad").html(formatNumber.new(Number(cantidad).toFixed(2)))
    }
  }).fail(function (jqxhr, textStatus, error) {
    var err = textStatus + ", " + error;
    console.log("Request Failed: " + err);
  });
}

function getInfoHoy() {
  $.ajax({
    type: "POST",
    url: base_url('index.php/Principal/infoHoy'),
    dataType: "json",
    data: {
      i: ini,
    }, 
  }).done(function (res) {

    let lpAct = res[3].cant

    $("#activos").text(lpAct)

  }).fail(function (jqxhr, textStatus, error) {
    var err = textStatus + ", " + error;
    console.log("Request Failed: " + err);
  });
}

function getNotaEntregaHoy() {
  ini = today
  $.ajax({
    type: "POST",
    url: base_url('index.php/Principal/notaEntregaHoy'),
    dataType: "json",
    data: {
      i: ini
    }, 
  }).done(function (res) {
    if (res[0].notaEntrega == null) {
      $("#notaEntrega").html('0.00 '+"<small> Bs</small>")
    } else {
        let ventasNE = res[0].notaEntrega
        $("#notaEntrega").html(formatNumber.new(Number(ventasNE).toFixed(2))+"<small> Bs</small>")
    }

  }).fail(function (jqxhr, textStatus, error) {
    var err = textStatus + ", " + error;
    console.log("Request Failed: " + err);
  });
}
function getVentaCajaHoy() {
  ini = today
  $.ajax({
    type: "POST",
    url: base_url('index.php/Principal/ventaCajaHoy'),
    dataType: "json",
    data: {
      i: ini
    }, 
  }).done(function (res) {
    if (res[0].ventaCaja == null) {
      $("#ventaCaja").html('0.00 '+"<small> Bs</small>")
    } else {
        let ventaCaja = res[0].ventaCaja
        $("#ventaCaja").html(formatNumber.new(Number(ventaCaja).toFixed(2))+"<small> Bs</small>")
    }
  }).fail(function (jqxhr, textStatus, error) {
    var err = textStatus + ", " + error;
    console.log("Request Failed: " + err);
  });
}

function newTable() {
  $.ajax({
  
    type: "POST",
    url: base_url('index.php/Principal/ventasGestion'),
    dataType: 'json',
    data: {
      i: '2017-01-01',
      f: '2019-12-01',
    }, 
    success: function (obj, textstatus) {
      $('#table_id thead tr').clone(true).appendTo( '#example thead' );
      $('#table_id thead tr:eq(1) th').each( function (i) {
          var title = $(this).text();
          $(this).html( '<input type="text" placeholder="Search '+title+'" />' );
  
          $( 'input', this ).on( 'keyup change', function () {
              if ( table.column(i).search() !== this.value ) {
                  table
                      .column(i)
                      .search( this.value )
                      .draw();
              }
          } );
      } );
  
            $('#table_id').DataTable({
              data: obj,
              fixedHeader: {
                      header: true,
              },
              order: [],
              paging: false,
              orderCellsTop: true,
              responsive: true,
              dom: 'Bfrtip',
              buttons: [
                        'colvis',
                        {
                          extend: 'copy',
                          text: '<i class="fa fa-files-o" style="color:red;">Copy</i>',
                          header : false,
                          title : null,
                          exportOptions: {
                              columns: [':visible' ],
                              title: null,
                              modifier: {
                                order: 'current',
                              }
                          }
                        },

                        'print'
                        ],
                columns: [
                          { 
                            data: 'id',
                            title: 'id'
                          }, 

                            { 
                              data: 'mes',
                              title: 'Month'
                            }, 
                            { 
                              data: 'gestion',
                              title: 'Gestión', 
                              //searchable:false,
                              //visible: false
                            },
                            { 
                              data: 'montoLP',
                              title: 'La Paz', 
                              className: 'text-right',
                              render:numberCoin,
                              
                            }, 
                            { 
                              data: 'montoPTS',
                              title: 'Potosì',  
                              className: 'text-right',
                              render:numberCoin,
                            }, 
                            { 
                              data: 'montoSCZ',
                              title: 'Santa Cruz', 
                              className: 'text-right',
                              render:numberCoin, 
                            }, 
                            { 
                              data: 'totalMes',
                              title: 'Total',
                              className: 'text-right',
                              render:numberCoin,  
                            }
                ],
               /*initComplete: function () {
                  var api = this.api();
                  api.columns([1,3]).indexes().flatten().each(function (i) {
                        var column = api.column(i);
                        var select = $('<select><option value=""></option></select>')
                          .appendTo( $(column.header()) )
                            .on('change', function () {
                                var val = $.fn.dataTable.util.escapeRegex(
                                    $(this).val()
                                );

                                column
                                  .search( val ? '^'+val+'$' : '', true, false )
                                  .draw();
                            });

                        column.data().unique().sort().each(function (d, j) {
                            select.append('<option value="' + d + '">' + d + '</option>')
                        });
                    });
                }*/
                /*initComplete: function () {
                  this.api().columns().every( function () {
                      var column = this;
                      var select = $('<select><option value=""></option></select>')
                          .appendTo( $(column.header()) )
                          .on( 'change', function () {
                              var val = $.fn.dataTable.util.escapeRegex(
                                  $(this).val()
                              );
       
                              column
                                  .search( val ? '^'+val+'$' : '', true, false )
                                  .draw();
                          } );
       
                      column.data().unique().sort().each( function ( d, j ) {
                          select.append( '<option value="'+d+'">'+d+'</option>' )
                      } );
                  } );
              }*/
            });
    },
    error: function (obj, textstatus) {
        alert(obj.msg);
    }
});
}
$(document).on("change", "#almacen_filtro", function () {
  showNegatives()
})

function showNegatives() {
  ges = '2021'
  alm = $("#almacen_filtro").val()
	$.ajax({
      url :  base_url('index.php/Principal/negatives'),
      type : 'POST',
      "dataSrc": "",
      dataType: "json",
      data: {
              g:ges,
              a:alm
        },
	}).done(function (res) {
    $("#negativos").text(res.length)
		table = $('#negatives').DataTable({
			data: res,
      responsive: true,
      destroy: true,
      dom: 'Bfrtip',
      pageLength: 5,
      stateSave: true,
      order: [],
			columns: [
        { 
            data: 'lineaS',
            title: 'Linea',
            visible: false
        }, 
        { 
            data: 'linea',
            title: 'linea',
        },
        { 
            data: 'codigo',
            title: 'Codigo',

        },
        { 
          data: 'descp',
          title: 'Descripción',

        },
        { 
          data: 'cantidadSaldo',
          title: 'Saldo',

        },



      ],
			stateSave: true,
			stateSaveParams: function (settings, data) {
				data.order = []
			},
			buttons: [
          
          
        {
            extend: 'copy',
            text: '<i class="fas fa-copy" style="font-size:18px;"> </i>',
            titleAttr: 'Copiar',
            header : false,
            title : null,
            exportOptions: {
                columns: [':visible' ],
                title: null,
                modifier: {
                order: 'current',
                }
            }
        },
        {
            extend: 'excel',
            text: '<i class="fas fa-file-excel" aria-hidden="true" style="font-size:18px;"> </i>',
            titleAttr: 'Exportar a Excel',
            autoFilter: true,
            //messageTop: 'The information in this table is copyright to Sirius Cybernetics Corp.',
            title: 'Negativos',
            exportOptions: {
                columns: ':visible'
            },
        },
        {
            extend: 'pdf',
            titleAttr: 'Exportar PDF',
            title: 'Negativos',
            text: '<i class="fas fa-print" aria-hidden="true" style="font-size:18px;"></i>',
        },
        {
            text: '<i class="fas fa-sync" aria-hidden="true" style="font-size:18px;"></i>',
            titleAttr: 'Recargar',
            action: function ( e, dt, node, config ) {
              showNegatives()
            }
        },
        {
            extend: 'collection',
            text: '<i class="fa fa-cogs" aria-hidden="true" style="font-size:18px;"></i>',
            titleAttr: 'Configuracion',
            autoClose: true,
            buttons: [
                'pageLength',
                {
                    extend: 'colvis',
                    text: '<i class="fas fa-eye" aria-hidden="true"> Ver/Ocultar</i>',
                    collectionLayout: 'fixed two-column',
                    postfixButtons: [ 'colvisRestore' ]
                },
                {
                    text: '<i class="fas fa-redo" aria-hidden="true"> Reestablecer</i>',
                    className: 'btn btn-link',
                    action: function ( e, dt, node, config ) {
                        this.state.clear()
                        showNegatives()
                    }
                },

            ]
        },
        
        
      ],
			order: [],
			fixedHeader: {
				header: true,
				footer: true
			},
			//paging: false,
			//responsive: true,
			language: {
        buttons: {

            colvisRestore: "Restaurar",
            copyTitle: 'Información copiada',
            pageLength: {
                            _: "VER %d FILAS",
                            '-1': "VER TODO"
                        },
            copySuccess: {
                            _: '%d lineas copiadas',
                            1: '1 linea copiada'
                        },
        },
        "decimal": "",
        "emptyTable": "No existen Negativos",
        "info": "Mostrando _START_ a _END_ de _TOTAL_ Negativos",
        "infoEmpty": "No existen Negativos",
        "infoFiltered": "(Filtrado de _MAX_ total entradas)",
        "infoPostFix": "",
        "thousands": ",",
        "lengthMenu": "Mostrar _MENU_ Registros",
        "loadingRecords": "Cargando...",
        "processing": "Procesando...",
        "search": "Buscar:",
        "zeroRecords": "Sin resultados encontrados",
        "paginate": {
            "first": "Primero",
            "last": "Ultimo",
            "next": "Siguiente",
            "previous": "Anterior"
        },
      },

		});
	}).fail(function (jqxhr, textStatus, error) {
		let err = textStatus + ", " + error;
		console.log("Request Failed: " + err);
	});
}