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
            $.ajax({
                type: "POST",
                url: base_url('index.php//siat/codigos/cuis/getAlmacenes'),
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
                            data: 'idalmacen',
                            title: 'id',
                            className: 'text-center',
                        },
                        {
                            data: 'almacen',
                            title: 'Nº',
                            className: 'text-center',
                        },
                        {
                            data: 'cuis',
                            title: 'CUIS',
                            className: 'text-center',
                        },
                        {
                            data: 'status',
                            title: 'ACTIVO',
                            //sorting: nameCliente == '' || nameCliente == 'TODOS' ? true : false
                        },
                        {
                            data: null,
                            title: '',
                            width: '120px',
                            className: 'text-center',
                            render: buttons
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
          
        }

	},
	filters:{
		moneda:function(value){
			num=Math.round(value * 100) / 100
			num=num.toFixed(2);
			//return(num);
			return numeral(num).format('0,0.00');            
		}, 
	}
  })