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
        showNE()
    });
    showNE()
})
$(document).on("change", "#almacen_filtro", function () {
    showNE()
})
$(document).on("change", "#cliente_egreso", function () {
    showNE()
})
$(document).on("change", "#tipoNota", function () {
    showNE()
})
$(document).on("click", "#refresh", function () {
    showNE()
})
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
    let tipoNota = $("#tipoNota").val();
    almText = $('#almacen_filtro').find(":selected").text();
    //idCliente = nameCliente == '' || nameCliente == 'TODOS' ? 'all' : $("#idCliente").val();
    tituloReporte()

    $('#datatable').DataTable({
       
        "ajax":{
                url :  base_url('index.php/Reportes/mostrarNEporFac'),
                type : 'POST',
                "dataSrc": "",
                dataType: "json",
                data: {
                        ini: ini,
                        fin: fin,
                        alm: alm,
                        c: 'all',
                        tipoNota: tipoNota
                    },
        },
        //"serverSide": true,
        responsive: true,
        destroy: true,
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
                visible: false,
                render:numberDecimal
            },
            { 
                data: 'autor',
                title: 'RESPONSABLE',
                className: 'text-right',
                sorting:false,
                render:cellEmpy
            },
            { 
                data: 'glosa',
                title: 'GLOSA',
                sorting:false,
                className: 'text-left',
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