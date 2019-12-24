let iniciofecha = moment().subtract(5, 'year').startOf('year')
let finfecha = moment().subtract(0, 'year').endOf('year')
var val
var table





$(document).ready(function () {
    
    $.fn.dataTable.ext.errMode = 'none';
     



   
    
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
    showNE()
    //retornarNEporFac();
})
$(document).on("change", "#almacen_filtro", function () {
    showNE()
    //retornarNEporFac();
})
$(document).on("change", "#cliente_egreso", function () {
    showNE()
    //retornarNEporFac();
})



$(document).on("click", "#refresh", function () {
    showNE()
    //retornarNEporFac()

})

function retornarNEporFac()
{

        

    tituloReporte()
    ini = iniciofecha.format('YYYY-MM-DD')
    fin = finfecha.format('YYYY-MM-DD')
    alm = $("#almacen_filtro").val() == '' ? 'all' : $("#almacen_filtro").val()
    nameCliente = $("#cliente_egreso").val();
    almText = $('#almacen_filtro').find(":selected").text();
    idCliente = nameCliente == '' || nameCliente == 'TODOS' ? 'all' : $("#idCliente").val();

    agregarcargando();
    $.ajax({
        type: "POST",
        url: base_url('index.php/Reportes/mostrarNEporFac'),
        dataType: "json",
        data: {
            i: ini,
            f: fin,
            a: alm,
            c: idCliente,
        },

    }).done(function (res)
     { 
            quitarcargando();
            table = $('#tablaNotasEntregaFacturar').DataTable({
            data: res,
            destroy: true,
            dom: 'Bfrtip',
            lengthMenu: [
                [ 10, 25, 50, -1 ],
                [ '10 filas', '25 filas', '50 filas', 'Todo' ]
            ],
            pageLength: 10,
            createdRow: function( row, res, dataIndex){
                if (res.idEgresos == null ) {
                    $(row).addClass('subtitle')
                }
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
                    visible: alm == 'all' ? true : false
                },
                { 
                    data: 'nombreCliente',
                    title: 'CLIENTE',
                    sorting: nameCliente == '' || nameCliente == 'TODOS' ? true : false
                },
                { 
                    data: 'n',
                    title: 'Nº',
                    sorting:false,
                    className: 'text-center',
                    render:cellEmpy
                }, 
                { 
                    data: 'total',
                    title: 'TOTAL BOB',
                    sorting:false,
                    className: 'text-right',
                    render:numberDecimal
                }, 
                { 
                    data: 'totalDol',
                    title: 'TOTAL $U$',
                    className: 'text-right',
                    sorting:false,
                    render:numberDecimal
                },
                { 
                    data: 'autor',
                    title: 'RESPONSABLE',
                    className: 'text-right',
                    sorting:false,
                    render:cellEmpy
                }

            ],
            stateSave: true,
            stateSaveParams: function (settings, data) {
                data.order = []
                alm == 'all' ? data.columns[1].visible=true : false
            },
            buttons: [
                
                
                {
                  extend: 'copy',
                  text: '<i class="fas fa-copy" style="font-size:18px;"> </i>',
                  titleAttr: 'Configuracion',
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
                    titleAttr: 'ExportExcel',
                    autoFilter: true,
                    //messageTop: 'The information in this table is copyright to Sirius Cybernetics Corp.',
                    title: 'Notas de Entrega Pendientes de Pago ',
                    exportOptions: {
                        columns: ':visible'
                    },
                },
                {
                    text: '<i class="fas fa-print" aria-hidden="true" style="font-size:18px;"></i>',
                    action: function ( e, dt, node, config ) {
                        window.window.print()
                    }
                },
                {
                    text: '<i class="fas fa-sync" aria-hidden="true" style="font-size:18px;"></i>',
                    action: function ( e, dt, node, config ) {
                        retornarNEporFac()
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
                                table.state.clear()
                                retornarNEporFac()
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
            responsive: true,
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
            
        });
        
        
        
 
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
    
    almText = $('#almacen_filtro').find(":selected").text() == 'TODOS' ? 'NIVEL NACIONAL' : $('#almacen_filtro').find(":selected").text()
    client = $('#cliente_egreso').val() == '' || $('#cliente_egreso').val() == 'TODOS' ? '' : $('#cliente_egreso').val()
    $('#titleAlm').text(almText);
    $('#titleClient').text(client);
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


function showNE() {
    ini = iniciofecha.format('YYYY-MM-DD')
    fin = finfecha.format('YYYY-MM-DD')
    alm = $("#almacen_filtro").val() == '' ? 'all' : $("#almacen_filtro").val()
    nameCliente = $("#cliente_egreso").val();
    almText = $('#almacen_filtro').find(":selected").text();
    idCliente = nameCliente == '' || nameCliente == 'TODOS' ? 'all' : $("#idCliente").val();
    tituloReporte()

    $('#datatable').DataTable({
       
        "ajax":{
                url :  base_url('index.php/Reportes/mostrarNEporFac'),
                type : 'POST',
                "dataSrc": "",
                dataType: "json",
                data: {
                        i: ini,
                        f: fin,
                        a: alm,
                        c: idCliente,
                    },
        },
        //"serverSide": true,
        responsive: true,
        //destroy: true,
        dom: 'Bfrtip',
        pageLength: 10,
        createdRow: function( row, res, dataIndex){
            if (res.idEgresos == null ) {
                $(row).addClass('subtitle')
            }
        },
        stateSave: true,
        stateSaveParams: function (settings, data) {
            console.log(this)
            data.order = []
            alm == 'all' ? data.columns[1].visible=true : false
        },
        order: [],
        fixedHeader: {
            header: true,
            footer: true
        },
            columns: [
            { 
                data: 'fechamov',
                title: 'FECHA',
                sorting: false,
                className: 'text-center',
                render:formato_fecha_corta_sub_ne,
            }, 
            { 
                data: 'almacen',
                title: 'ALMACEN',
                render:cellEmpy,
                visible: alm == 'all' ? true : false
            },
            { 
                data: 'nombreCliente',
                title: 'CLIENTE',
                sorting: nameCliente == '' || nameCliente == 'TODOS' ? true : false
            },
            { 
                data: 'n',
                title: 'Nº',
                sorting:false,
                className: 'text-center',
                render:cellEmpy
            }, 
            { 
                data: 'total',
                title: 'TOTAL BOB',
                sorting:false,
                className: 'text-right',
                render:numberDecimal
            }, 
            { 
                data: 'totalDol',
                title: 'TOTAL $U$',
                className: 'text-right',
                sorting:false,
                render:numberDecimal
            },
            { 
                data: 'autor',
                title: 'RESPONSABLE',
                className: 'text-right',
                sorting:false,
                render:cellEmpy
            }

        ],
        buttons: [
            
            
            {
                extend: 'copy',
                text: '<i class="fas fa-copy" style="font-size:18px;"> </i>',
                titleAttr: 'Configuracion',
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
                titleAttr: 'ExportExcel',
                autoFilter: true,
                //messageTop: 'The information in this table is copyright to Sirius Cybernetics Corp.',
                title: 'Notas de Entrega Pendientes de Pago ',
                exportOptions: {
                    columns: ':visible'
                },
            },
            {
                text: '<i class="fas fa-print" aria-hidden="true" style="font-size:18px;"></i>',
                action: function ( e, dt, node, config ) {
                    window.window.print()
                }
            },
            {
                text: '<i class="fas fa-sync" aria-hidden="true" style="font-size:18px;"></i>',
                action: function ( e, dt, node, config ) {
                    retornarNEporFac()
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
                            retornarNEporFac()
                        }
                    },

                ]
            },
            
            
        ],
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
                paging: true,
                //orderCellsTop: true,
                responsive: true,
                dom: 'Bfrtip',
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