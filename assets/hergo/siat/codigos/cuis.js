$(document).on("click", "button.get", function () {
	let row = getRow(table, this)
	cuis.getCuis(row)
})
const cuis = new Vue({
	el: '#app',
	data: {
		cuis: ''
	},
    mounted() {
		this.getAlmacenes()
	},
	methods:{
        getAlmacenes(){
            $.fn.dataTable.ext.errMode = 'none';
            $.ajax({
                type: "POST",
                url: base_url('index.php/siat/codigos/cuis/getAlmacenes'),
                dataType: "json",
            }).done(function (res) {
                console.log(res);
                table = $('#table').DataTable({
                    data: res,
                    destroy: true,
                    dom: 'Bfrtip',
                    responsive: true,
                    lengthMenu: [
                        [10, 25, 50, -1],
                        ['10 filas', '25 filas', '50 filas', 'Todo']
                    ],
                    pageLength: 10,
                    columns: [
                        {
                            data: 'id',
                            title: 'id',
                            className: 'text-center',
                        },
                        {
                            data: 'almacen',
                            title: 'Nº',
                            className: 'text-center',
                        },
                        {
                            data: 'siat_sucursal',
                            title: 'Sucursal',
                            className: 'text-center',
                        },
                        {
                            data: 'cuis',
                            title: 'CUIS',
                            className: 'text-center',
                        },
                        {
                            data: 'codigoPuntoVenta',
                            title: 'Punto de Venta',
                            className: 'text-center',
                        },
                        {
                            data: 'fechaVigencia',
                            title: 'Vigencia',
                            className: 'text-center',
                        },
                        {
                            data: 'status',
                            title: 'Estado',
                            className: 'text-center',
                        },
                        {
                            data: null,
                            title: '',
                            width: '120px',
                            className: 'text-center',
                            render: cuis.buttons
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
                            titleAttr: 'Configuracion',
                            header: false,
                            title: null,
                            exportOptions: {
                                columns: [':visible'],
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
                            title: 'Pedidos ',
                            exportOptions: {
                                columns: ':visible'
                            },
                        },
                        {
                            text: '<i class="fas fa-print" aria-hidden="true" style="font-size:18px;"></i>',
                            action: function (e, dt, node, config) {
                                window.window.print()
                            }
                        },
                        {
                            text: '<i class="fas fa-sync" aria-hidden="true" style="font-size:18px;"></i>',
                            action: function (e, dt, node, config) {
                                getPedidos()
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
                                    postfixButtons: ['colvisRestore']
                                },
                                {
                                    text: '<i class="fas fa-redo" aria-hidden="true"> Reestablecer</i>',
                                    className: 'btn btn-link',
                                    action: function (e, dt, node, config) {
                                        table.state.clear()
                                        getPedidos()
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
            });
        },
        getCuis(row){
            $.ajax({
                type: "POST",
                url: base_url_siat('codigos/cuis'),
                dataType: "json",
                data: {
                        "cliente": {
                            "codigoSucursal": row.siat_sucursal,
                            "codigoPuntoVenta": row.codigoPuntoVenta
                        }
                },
            }).done(function (res) {
                let cuis = res.RespuestaCuis.codigo
                let fechaVigencia = res.RespuestaCuis.fechaVigencia
                let status = res.RespuestaCuis.transaccion
                if (status) {
                    $.ajax({
                        type: "post",   
                        url: base_url('index.php/siat/codigos/Cuis/store'),
                        dataType: "json",   
                        data: {
                            sucursal: row.siat_sucursal,
                            cuis: cuis,
                            fechaVigencia: fechaVigencia,
                            codigoPuntoVenta: CPV,
                        },                                    
                    }).done(function(res){
                            console.log(res);
                            swal({
                                title: 'CUIS Guardado!',
                                text: "El Código Único de Inicio de Sistema fue guardado exitosamente.",
                                type: 'success', 
                                showCancelButton: false,
                            })
                            return 
                    }) 
                } else {
                    swal({
                        title: 'Error ',
                        text: res.RespuestaCuis.mensajesList.descripcion,
                        type: 'error', 
                        showCancelButton: false,
                    })
                    return
                }

            }).fail(function (jqxhr, textStatus, error) {
                let err = textStatus + ", " + error;
                console.log("Request Failed: " + err);
            });
        },
        buttons(){
            return `
                <button type="button" class="btn btn-default get">
                    <span class="fa fa-get-pocket" aria-hidden="true">
                    </span>
                </button>
            `
        }

	},
  })