let ini = moment().subtract(0, 'year').startOf('year').format('YYYY-MM-DD')
let fin = moment().subtract(0, 'year').endOf('year').format('YYYY-MM-DD')
$(document).ready(function () {
	$.fn.dataTable.ext.errMode = 'none';
	dataPicker()
	getPedidos()
	$('#reportrange').on('apply.daterangepicker', function (ev, picker) {
		getPedidos()
	});
})

function getPedidos() {
	agregarcargando()
	$.ajax({
		type: "POST",
		url: base_url('index.php/Importaciones/Pedidos/getPedidos'),
		dataType: "json",
		data: {
						ini:ini,
						fin:fin,
						condicion:'aprobadosNoOrden'

					},
	}).done(function (res) {
        data = res//res.filter(item  => item.nAprobados>2)
		table = $('#table').DataTable({
			data: data,
			destroy: true,
			dom: 'Bfrtip',
			lengthMenu: [
				[5, 10, -1],
				['5 filas','10 filas', 'Todo']
			],
			pageLength: 5,
			columns: [
				/* {
					data: 'id',
					title: 'id',
					className: 'text-center',
				}, */
				{
					data: 'nPedido',
					title: 'Nº',
					className: 'text-center',
				},
				{
					data: 'fecha',
					title: 'FECHA',
					className: 'text-center',
					render: formato_fecha,
				},
				{
					data: 'proveedor',
					title: 'PROVEEDOR',
				},
				{
					data: 'recepcion',
					title: 'RECEPCION',
					className: 'text-center',
					render: formato_fecha,
				},
				{
					data: 'formaPago',
					title: 'FORMA DE PAGO',
				},
				{
					data: 'pedidoPor',
					title: 'PEDIDO POR',
				},
				{
					data: 'total$',
					title: 'TOTAL $U$',
					className: 'text-right',
					sorting: false,
					render: numberDecimal
				},
				{
					data: 'nAprobados',
					title: 'ESTADO',
					className: 'text-center',
                    //render:aprobados
                    visible: false
				},
				{
					data: 'autor',
					title: 'CREADO POR',
					className: 'text-right',
					sorting: false,
					visible: false
				},
				{
					data: 'created_at',
					title: 'CREADO EN',
					className: 'text-center',
					//render: formato_fecha_corta,
					visible: false
				},
				{
					data: null,
					title: '',
					width: '100px',
					className: 'text-center',
					render: button
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
					title: 'Notas de Entrega Pendientes de Pago ',
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
		quitarcargando();
	}).fail(function (jqxhr, textStatus, error) {
		let err = textStatus + ", " + error;
		console.log("Request Failed: " + err);
	});
}
$(document).on("click", "button.add", function () {
	let row = table.row( $(this).parents('tr') ).data();
	ordenForm.getPedido(row.id_pedido)
	//console.table(row);
})
function button (data, type, row) {
    return `
    <button type="button" class="btn btn-default add" aria-label="Right Align">
        <span class="glyphicon glyphicon-plus" aria-hidden="true">
        </span>
    </button>
    `
}





Vue.component("v-select", VueSelect.VueSelect);
const ordenForm = new Vue({
    el: '#ordenForm',
    components: {
        vuejsDatepicker
    },
    data:{
        title: 'ORDEN DE COMPRA',
        fecha:'',
        proveedor:'',
        direccion:'',
        fono:'',
        fax:'',
        formaEnvio:'',
        condicionCompra:'',
        atencion:'',
        referencia:'',
        glosa:'',
        idPedido:'',
		formaPago:'',
		diasCredito:'',
        items:'',
        totalDoc:0,
		n:'',
		idOrden:'',
		es: vdp_translation_es.js,
		btnGuardar:'Guardar',
    },
    created: function() {
		let id = document.getElementById("idOrden").value
		if (id) {
		  console.log(id);
		  this.editOrden(id)
		}
    },
    methods:{
        getPedido(id){
			$.ajax({
				type: "POST",
				url: base_url('index.php/Importaciones/Pedidos/getPedido'),
				dataType: "json",
				data: {
						id:id,
					  },
			  }).done(function (res) {
                console.log(res);
                let num = '000'.substring(0, '000'.length - res.pedido.n.length) + res.pedido.n
                let year = moment(res.pedido.fecha).format('YY')
                ordenForm.referencia = `CONFIRMACION PEDIDO HG.- ${num}/${year}`
				ordenForm.idPedido = res.pedido.id
				ordenForm.fecha = moment(res.pedido.fecha).format('MM-DD-YYYY')
				ordenForm.formaPago = res.pedido.formaPago
				ordenForm.proveedor = res.pedido.proveedor
				ordenForm.direccion = res.pedido.direccion
				ordenForm.fono = res.pedido.telefono
				ordenForm.fax = res.pedido.fax
				ordenForm.diasCredito = res.pedido.diasCredito
				ordenForm.items = res.items
				ordenForm.total()
			  })
        },
        total(){
            if (this.items.length>0) {
                this.totalDoc = this.items.map((item, index, array) => parseFloat(item.total)).reduce( (a,b)=> a+b)
                return this.totalDoc
            }
        },
        store(e){
            agregarcargando()
            e.preventDefault()
            /* if (!this.selectedProv || !this.formaPago || !this.items.length>0) {
            quitarcargando()
            swal({
                title: 'Error',
                text: "Por favor llene correctamente el formulario",
                type: 'error', 
                showCancelButton: false,
            })
            return
            } */
            let form = new FormData();
            form.append('id', this.idOrden)
			form.append('fecha', moment(this.fecha).format('YYYY-MM-DD'))
			form.append('id_pedido', this.idPedido)
            form.append('n', this.n)
            form.append('atencion', this.atencion)
            form.append('referencia', this.referencia)
            form.append('condicion', this.condicionCompra)
			form.append('formaEnvio', this.formaEnvio)
            form.append('diasCredito', this.diasCredito)
			form.append('glosa', this.glosa)
		

            /* for(let pair of form.entries()) { console.log(pair[0]+ ', '+ pair[1]); }; 
            quitarcargando()
            return */
            $.ajax({
            url: base_url('index.php/Importaciones/OrdenesCompra/store'),
            type: "post",      
            data: form,                                    
            processData: false,
            contentType: false,
            cache:false, 
            }).done(function(res){
            res = JSON.parse(res)
            console.table(res);
            if (res.status == true) {
                quitarcargando()
                if (ordenForm.idOrden) {
                console.log(this.id);
                swal({
                    title: "Editado!",
                    text: "La orden se modificó con éxito",
                    type: "success",        
                    allowOutsideClick: false,                                                                        
                    }).then(function(){
                    agregarcargando()
                    window.location.href=base_url("index.php/Importaciones/OrdenesCompra");
                    })
                } else {
                swal({
                    title: "Guardado!",
                    text: "La orden se guardó con éxito",
                    type: "success",        
                    allowOutsideClick: false,                                                                        
                    }).then(function(){
                    agregarcargando()
                    location.reload()
                    })
                }
            } else {
                quitarcargando()
                swal({
                title: 'Error',
                text: "Error al guardar la solicitud, verifique el tipo de cambio para la fecha.",
                type: 'error', 
                showCancelButton: false,
                })
                return
            }
            
            }) 
        },
        cancel(){
            console.log('cancel');
		},
		customFormatter(date) {
			return moment(date).format('D MMMM  YYYY');
		},
		editOrden(id){
			this.idOrden = id
			this.btnGuardar = 'Editar'
			$.ajax({
			  type: "POST",
			  url: base_url('index.php/Importaciones/OrdenesCompra/getOrden'),
			  dataType: "json",
			  data: {
					  id:id,
					},
			}).done(function (res) {
			  console.log(res);
			  ordenForm.n = res.orden.n
			  ordenForm.idPedido = res.orden.id_pedido
			  ordenForm.title = 'ORDEN DE COMPRA N° ' + res.orden.n
			  ordenForm.fecha = moment(res.orden.fecha).format('MM-DD-YYYY')
			  ordenForm.proveedor = res.orden.nombreproveedor
			  ordenForm.atencion = res.orden.atencion
			  ordenForm.condicionCompra = res.orden.condicion
			  ordenForm.direccion = res.orden.direccion
			  ordenForm.fax = res.orden.fax
			  ordenForm.fono = res.orden.telefono
			  ordenForm.referencia = res.orden.referencia
			  ordenForm.formaEnvio = res.orden.formaEnvio
			  ordenForm.formaPago = res.orden.formaPago
			  ordenForm.glosa = res.orden.glosa
			  ordenForm.diasCredito = res.orden.diasCredito
			  ordenForm.items = res.items
			  ordenForm.total()
			})
		  },


    },
    filters:{
        moneda:function(value){
            num=Math.round(value * 100) / 100
            num=num.toFixed(2);
            //return(num);
            return numeral(num).format('0,0.00');            
        },                 
    },  
})