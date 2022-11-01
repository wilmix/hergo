let columns = [
    {
        data: 'codigoClasificador',
        title: 'codigoClasificador',
        className: 'text-center',
    },
    {
        data: 'descripcion',
        title: 'descripcion',
        className: 'text-center',
    },
]

Vue.component('v-select', VueSelect.VueSelect);
const sincro = new Vue({
	el: '#app',
	data: {
		almacenes: [],
        almacen:[],
        metodos:[],
        metodo:'',
        datasiat:false
	},
    mounted() {
		this.getAlmacenes()
        this.getMetodos()
	},
	methods:{
        getAlmacenes(){
            $.ajax({
                type: "POST",
                url: base_url('index.php/siat/codigos/cuis/getAlmacenes'),
                dataType: "json",
            }).done(function (res) {
                sincro.almacenes = res
                console.log(res);
            });
        },
        getMetodos(){
            $.ajax({
                type: "GET",
                url: base_url_siat('sincronizacion/metodos'),
                dataType: "json",
            }).done(function (res) {
                filtrados = res.filter(text => text.substring(0, 22) == 'sincronizarParametrica')
                filtrados= filtrados.map(text => text.trim())
                sincro.metodos = filtrados
            });
        },
        getData(){
            if (sincro.almacen == '' || sincro.almacen == null) {
                swal({
                    title: 'Error ',
                    text: 'Seleccione almacen válido.',
                    type: 'error', 
                    showCancelButton: false,
                })
                return
            } else {
                agregarcargando()
                $.ajax({
                    type: "POST",
                    url: base_url_siat('sincronizar'),
                    dataType: "json",
                    data:{
                        cliente: {
                            cuis: sincro.almacen.cuis,
                            codigoSucursal: sincro.almacen.siat_sucursal,
                            codigoPuntoVenta: sincro.almacen.codigoPuntoVenta,
                            method: sincro.metodo
                        }
                    }
                }).done(function (res) {
                    quitarcargando()
                    sincro.datasiat = res.RespuestaListaParametricas.listaCodigos
                    table = sincro.table(sincro.datasiat, columns)
                });
            }

        },
        table(data, columsn){
            return $('#table').DataTable({
                data: data,
                destroy: true,
                dom: 'Bfrtip',
                responsive: true,
                lengthMenu: [
                    [10, 25, 50, -1],
                    ['10 filas', '25 filas', '50 filas', 'Todo']
                ],
                pageLength: 10,
                columns: columsn,
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
                language: datatableLangage
    
            });
        },
        sincronizar(metodo){
            agregarcargando()
            if (sincro.datasiat) {
                $.ajax({
                    type: "post",   
                    url: base_url('index.php/siat/sincronizacion/Sincronizar/' + metodo),
                    dataType: "json",   
                    data: {
                        dataSiat: sincro.datasiat
                    },                                    
                }).done(function(res){
                        if (res) {
                            quitarcargando()
                            swal({
                                title: 'OK',
                                text: "Las actividades se sincronizaron exitosamente",
                                type: 'success', 
                                showCancelButton: false,
                            })
                            return 
                        } else {
                            quitarcargando()
                            swal({
                                title: 'Error ',
                                text: 'Hubo cambios en el catalogo favor revisar',
                                type: 'error', 
                                showCancelButton: false,
                            })
                        }
                        
                }) 
            } else {
                quitarcargando()
                swal({
                    title: 'Error ',
                    text: 'No existen datos a sincronizar',
                    type: 'error', 
                    showCancelButton: false,
                })
                return
            }
        }
        

	},
  })