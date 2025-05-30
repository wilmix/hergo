$(document).on("click", "button.get", function () {
	let row = getRow(table, this)
	cufd.getCufd(row)
})
Vue.component('v-select', VueSelect.VueSelect);
const cufd = new Vue({
	el: '#app',
	data: {
		almacenes: [],
        almacen:[],
        infoSincro:0
	},
    mounted() {
		this.getAlmacenes()
        this.getCufdTable()
        this.getDataSincroInventarios()
	},
	methods:{
        getAlmacenes(){
            $.ajax({
                type: "POST",
                url: base_url('index.php/siat/codigos/cuis/getAlmacenes'),
                dataType: "json",
            }).done(function (res) {
                cufd.almacenes = res
            });
        },
        getDataSincroInventarios(){
            agregarcargando()
            $.ajax({
                type: "POST",
                url: base_url('siat/sincronizacion/Sincronizar/sincroCatalogosUltimas24Horas'),
                dataType: "json",
            }).done(function (res) {
                quitarcargando()
                //sincro.cantidadSincronizados = Object.keys(res).length
                cufd.infoSincro = res
            });
        },
        getCufd(row){
            agregarcargando()
            /* if (Object.keys(cufd.infoSincro).length !== 17) {
                quitarcargando()
                swal({
                    title: `Error`,
                    html: `Se produjo un error con la sincronizacion de datos PENDIENTE (${17 - Object.keys(cufd.infoSincro).length})`,
                    type: 'error', 
                    showCancelButton: false,
                })
                return false
            } */

            $.ajax({
                type: "POST",
                url: base_url_siat('codigos/cufd'),
                dataType: "json",
                data:{
                        cuis: row.cuis,
                        codigoSucursal: row.siat_sucursal,
                        codigoPuntoVenta: row.codigoPuntoVenta
                }
            }).done(function (res) {
                if (res.hasOwnProperty('errors') && res.errors.status == 422) {
                    quitarcargando()
                    swal({
                        title: `Error ${res.errors.title}`,
                        html: `Se produjo un error de conexión 
                                <br> status: ${res.errors.status}
                                <br> source: ${res.errors.source}`,
                        type: 'error', 
                        showCancelButton: false,
                    })
                } else if (res.RespuestaCufd.transaccion) {
                    quitarcargando();
                    swal({
                        title: 'CUFD Generado!',
                        html: `Código Unico de Facturación Diaria fue generado y guardado exitosamente para ${cufd.almacen.almacen}, <br>
                        vigente hasta ${res.RespuestaCufd.fechaVigencia}`,
                        type: 'success', 
                        showCancelButton: false,
                    })
                    cufd.getCufdTable()
                    return 
                    $.ajax({
                        type: "post",   
                        url: base_url('index.php/siat/codigos/Cufd/store'),
                        dataType: "json",   
                        data: {
                            codigo: res.RespuestaCufd.codigo,
                            codigoControl: res.RespuestaCufd.codigoControl,
                            fechaVigencia: res.RespuestaCufd.fechaVigencia,
                            cuis: row.cuis,
                        },                                    
                    }).done(function(res){
                            quitarcargando();
                            swal({
                                title: 'CUFD Generado!',
                                html: `Código Unico de Facturación Diaria fue generado y guardado exitosamente para ${cufd.almacen.almacen}, <br>
                                vigente hasta ${res.fechaVigencia}`,
                                type: 'success', 
                                showCancelButton: false,
                            })
                            cufd.getCufdTable()
                            return 
                    }) 
                } else {
                    console.log('error');
                }
                

                
            });
        },
        getCufdTable(){
            $.fn.dataTable.ext.errMode = 'none';
            $.ajax({
                type: "POST",
                url: base_url('index.php/siat/codigos/Cufd/getCufdList'),
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
                            data: 'sucrusal',
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
                            data: 'codigoControl',
                            title: 'codigoControl',
                            className: 'text-center',
                            //visible: false
                        },
                        {
                            data: 'codigo',
                            title: 'codigo',
                            className: 'text-center',
                            //visible: false
                        },
                        {
                            data: 'ini',
                            title: 'ini',
                            className: 'text-center',
                        },
                        {
                            data: 'fin',
                            title: 'fin',
                            className: 'text-center',
                        },
                        {
                            data: 'estado',
                            title: 'estado',
                            className: 'text-center',
                        },
                        {
                            data: null,
                            title: '',
                            width: '120px',
                            className: 'text-center',
                            render: cufd.buttons
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
                                cufd.getCufdTable()
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
                                        cufd.getCufdTable()
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
                    language: datatableLangage
                });
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
                    sincro.datasiat = res.RespuestaListaParametricas.listaCodigos
                    table = sincro.table(sincro.datasiat, columns)
                });
            }

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