let iniciofecha = moment().subtract(5, 'year').startOf('year')
let finfecha = moment().subtract(0, 'year').endOf('year')
var val
$(document).ready(function () {
    
   
    tituloReporte()
    $(".tiponumerico").inputmask({
        alias: "decimal",
        digits: 2,
        groupSeparator: ',',
        autoGroup: true,
        autoUnmask: true
    });

    let start = moment().subtract(5, 'year').startOf('year')
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
        retornarNEporFac();
    });
    retornarNEporFac();
})
$(document).on("change", "#almacen_filtro", function () {

    retornarNEporFac();
})
$(document).on("change", "#cliente_egreso", function () {

    retornarNEporFac();
})



$(document).on("click", "#refresh", function () {
    
    retornarNEporFac()

})
    var table
function retornarNEporFac()
{

        

    tituloReporte()
    ini = iniciofecha.format('YYYY-MM-DD')
    fin = finfecha.format('YYYY-MM-DD')
    alm = $("#almacen_filtro").val();
    nameCliente = $("#cliente_egreso").val();
    almText = $('#almacen_filtro').find(":selected").text();
    idCliente = nameCliente == '' || nameCliente == '' ? 'TODOS' : $("#idCliente").val();

    agregarcargando();
    $.ajax({
        type: "POST",
        url: base_url('index.php/Reportes/mostrarNEporFac'),
        dataType: "json",
        data: {
            i: ini,
            f: fin,
            a: alm,
            c: idCliente
        },

    }).done(function (res)
     {
            quitarcargando();
            //$("#tablaNotasEntregaFacturar").DataTable('destroy');    
            //console.log($("#tablaNotasEntregaFacturar"));        
            table = $('#tablaNotasEntregaFacturar').DataTable({
            data: res,
            destroy: true,
            dom: 'Bfrtip',
            createdRow: function( row, res, dataIndex){
                if (res.idEgresos == null ) {
                    $(row).addClass('subtitle')
                }
            },
            //orderCellsTop: true,
            //filter:true,
            info:false,
            //stateSave: true,
            stateSaveParams: function (settings, data) {
                //delete data.order;
                data.order = [];
            },
            buttons: [
                {
                    extend: 'colvis',
                    text: '<i class="fa fa-eye" aria-hidden="true" style="font-size:18px;"> </i>',
                    //collectionLayout: 'fixed two-column',
                    className: 'btn btn-secondary',
                    postfixButtons: [ 'colvisRestore' ]
                },
                
                {
                  extend: 'copy',
                  text: 'Copiar',
                  className: 'btn btn-primary',
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
                    className: 'btn btn-success',
                    autoFilter: true,
                    //messageTop: 'The information in this table is copyright to Sirius Cybernetics Corp.',
                    title: 'Notas de Entrega Pendientes de Pago ',
                    exportOptions: {
                        columns: ':visible'
                    },
                    /*customize: function( xlsx ) {
                        var sheet = xlsx.xl.worksheets['sheet1.xml'];
         
                        $('row c[r^="C"]', sheet).attr( 's', '2' );
                    }*/

                },
                /*{
                    extend: 'pdf',
                    className: 'btn btn-danger',
                    //messageTop: 'PDF created by PDFMake with Buttons for DataTables.',
                    pageSize: 'LETTER',
                    title: 'Notas de Entrega Pendientes de Pago\n'+ nameCliente + '\n' + almText ,
                    customize: function(doc) {
                       console.dir(doc.content[1])
                        doc.content[1].table.widths = [ '15%', '40%', '5%', '10%', '10%','20%']
                        console.dir(doc.content[1])

                        //doc.content[1].table.widths[0] = 200
                        let countRow = doc.content[1].table.body.length
                        console.log(countRow);
                        for (i = 1; i < countRow; i++) {
                            doc.content[1].table.body[i][2].alignment = 'center';
                            doc.content[1].table.body[i][3].alignment = 'right';
                            doc.content[1].table.body[i][4].alignment = 'right';
                            //doc.content[1].table.body[i][5].alignment = 'right';
                        }
                        return false
                        
                        doc.content[1].margin = [ 10, 0, 10, 0 ]
                        //doc.content[1].width = '*'
                        /*if (idCliente =='TODOS') {
                            doc.content[1].margin = [ 0, 0, 0, 0 ] //left, top, right, bottom
                        } else {
                            doc.content[1].margin = [ 100, 0, 100, 0 ]
                        }

                    },
                    exportOptions: {
                        columns: ':visible',
                        stripNewlines: false
                    },
                },*/
                {
                    text: '<i class="fa fa-print" aria-hidden="true" style="font-size:18px;"></i>',
                    className: 'btn btn-danger',
                    action: function ( e, dt, node, config ) {
                        window.window.print()
                    }
                },
                {
                    text: '<i class="fa fa-refresh" aria-hidden="true" style="font-size:18px;"></i>',
                    className: 'btn btn-info',
                    action: function ( e, dt, node, config ) {
                        table.state.clear()
                        window.location.reload()
                    }
                },
            ],
            order: [],
            fixedHeader: {
                header: true,
                footer: true
            },
            paging: false,
            responsive: true,
            sorting:false,
            language: {
                buttons: {
                    colvisRestore: "Restaurar",
                    copyTitle: 'Información copiada',
                    copySuccess: {
                        _: '%d lineas copiadas',
                        1: '1 linea copiada'
                    },
                },
                "decimal": "",
                    "emptyTable": "No hay información",
                    "info": "Mostrando _START_ a _END_ de _TOTAL_ Entradas",
                    "infoEmpty": "Mostrando 0 to 0 of 0 Entradas",
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
            columns: [
                { 
                    data: 'fecha',
                    title: 'FECHA',
                    sorting: false,
                    className: 'text-center',
                    render:formato_fecha_corta_sub_ne,
                }, 
                { 
                    data: 'almacen',
                    title: 'ALMACEN',
                    render:cellEmpy,
                    visible: alm == '' ? true : false
                },
                { 
                    data: 'nombreCliente',
                    title: 'CLIENTE',
                    sorting: false
                },
                { 
                    data: 'n',
                    title: 'Nº',
                    sorting: false,
                    className: 'text-center',
                    render:cellEmpy
                }, 
                { 
                    data: 'total',
                    title: 'TOTAL BOB',
                    className: 'text-right',
                    render:numberDecimal
                }, 
                { 
                    data: 'totalDol',
                    title: 'TOTAL $U$',
                    className: 'text-right',
                    render:numberDecimal
                },
                { 
                    data: 'autor',
                    title: 'RESPONSABLE',
                    className: 'text-right',
                    render:cellEmpy
                }

            ],
        });
        //$("#tablaNotasEntregaFacturar").append('<tfoot><tr><th>fecha</th><th></th></tr></tfoot>');

 
    }).fail(function (jqxhr, textStatus, error) {
        var err = textStatus + ", " + error;
        console.log("Request Failed: " + err);
    });

}

function prueba() {
    console.log('object');
    $('<tfoot/>').append( $("#tablaNotasEntregaFacturar thead tr").clone() )
    console.log($("#tablaNotasEntregaFacturar thead tr"));
}

function tituloReporte() {
    
    almText = $('#almacen_filtro').find(":selected").text();
    $('#tituloReporte').text(almText);
    $('#ragoFecha').text("DEL " + iniciofecha.format('DD/MM/YYYY') + "  AL  " + finfecha.format('DD/MM/YYYY'));
}
function cellEmpy(data, type, row){
    let valor
    if (row.idEgresos==null) {
        valor = ''
    } else {
        valor = data
    }
    return valor
}
function formato_fecha_corta_sub_ne(data, type, row) {
    let fecha = ''
    if (row.idEgresos==null) {
      fecha = ''
    } else {
      if ((data == "0000-00-00 00:00:00") || (data == "") || (data == null))
        fecha = "sin fecha de registro"
      else
        fecha = moment(data, "YYYY/MM/DD HH:mm:ss").format("DD/MM/YYYY")
    }
    return fecha
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