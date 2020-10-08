let ini = moment().subtract(0, 'year').startOf('year').format('YYYY-MM-DD')
let fin = moment().subtract(0, 'year').endOf('year').format('YYYY-MM-DD')
$(document).ready(function () {
	$.fn.dataTable.ext.errMode = 'none';
	dataPicker()
	getBackOrders()
	$('#reportrange').on('apply.daterangepicker', function (ev, picker) {
		getBackOrders()
	});

})
$(document).on("change", "#filter", function () {
    getBackOrders()
})

function getBackOrders() {
	agregarcargando()
	let filter =  $("#filter").val()
    //console.log(ini + ' -  ' + fin);
	$.ajax({
		type: "POST",
		url: base_url('index.php/Importaciones/BackOrder/getBackOrderList'),
		dataType: "json",
		data: {
						ini:ini,
						fin:fin,
						filtro: filter
					},
	}).done(function (res) {
		table = $('#table').DataTable({
			data: res,
			destroy: true,
            dom: 'Bfrtip',
            responsive: true,
			lengthMenu: [
				[10, 25, 50, -1],
				['10 filas', '25 filas', '50 filas', 'Todo']
			],
			createdRow: function( row, res, dataIndex ) {
				if ( res.status == 1) {
				  	$(row).addClass( 'styleGreen' );
				}
			},
			pageLength: 10,
			columns: [
				{
					data: 'orden',
					title: 'PEDIDO',
					className: 'text-center',
                },
                {
					data: 'proveedor',
					title: 'PROVEEDOR',
                    className: 'text-left',
                    visible:false
                },
                {
					data: 'codigo',
					title: 'CODIGO',
					className: 'text-left',
                },
                {
					data: 'descripcion',
					title: 'DESCRIPCION',
					className: 'text-left',
                },
                {
					data: 'formaEnvio',
					title: 'TRANSP.',
					className: 'text-center',
                },

                {
					data: 'fechaOrden',
					title: 'FECHA ORDEN',
					className: 'text-center',
					render: formato_fecha,
                },
                {
					data: 'estimada',
					title: 'RECECIÒN ESTIMADA',
					className: 'text-center',
					render: formato_fecha,
                },
				{
					data: 'totalOrden',
					title: 'TOTAL ORDEN',
					className: 'text-right',
					sorting: false,
					render: numberDecimal
                },
                {
					data: 'estado',
					title: 'ESTADO',
					className: 'text-right',
                },
                {
					data: 'recepcion',
					title: 'FECHA RECEPCIÒN',
					className: 'text-center',
					render: formato_fecha,
                },
                {
					data: 'embarque',
					title: 'EMBARQUE',
					className: 'text-right',
                },
				{
					data: null,
					title: '',
					width: '100px',
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
						getBackOrders()
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
								getBackOrders()
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

function buttons (data, type, row) {
	return `
	<button type="button" class="btn btn-default edit">
		<span class="fa fa-pencil" aria-hidden="true">
		</span>
	</button>
`
}
$(document).on("click", "button.edit", function () {
	let row = getRow(table, this)
	console.log(row);
    back.row = row
    back.modal()
})

Vue.component('modal', {
    template: '#modal-template',
    props:['row','index'],
	components: {
        vuejsDatepicker
	},
	data: function(){
        return{
            es: vdp_translation_es.js, 
            id:back.row.id,
            idPedido:back.row.idPedido,
            pedidoItem: 'pedido',
            status: back.row.status,
            pedido:back.row.orden,
            proveedor:back.row.proveedor,
            codigo:back.row.codigo,
            descripcion:back.row.descripcion,
            estimada:moment(back.row.estimada).format('DD MMMM YYYY'),
            recepcion:moment(back.row.recepcion).format('DD MMMM YYYY'),
            embarque:back.row.embarque,
			estado:back.row.estado,
			errors:''             
        }
    },
	methods:{     
		customFormatter(date) {
			return moment(date).format('D MMMM  YYYY');
		},
		close() {
			 back.showModal = false
		},
		saveStatus(){
			agregarcargando()

            let formData = new FormData($('#form')[0]); 
            formData.append('id', this.id)
            formData.append('idPedido', this.idPedido)
			formData.append('fecha', moment(this.recepcion).format('YYYY-MM-DD'))

			for(let pair of formData.entries()) {
				console.log(pair[0]+ ', '+ pair[1]); 
			} 
			this.close()
			/* quitarcargando()
			return */
			$.ajax({
				url: base_url("index.php/Importaciones/BackOrder/saveStatus"),
				type: 'POST',
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				success: function (returndata) {
					res = JSON.parse(returndata)
					console.log(res);
					if (res != false) {
						quitarcargando()
						swal({
							title: "Registrado!",
							text: "El estado se registro con èxito",
							type: "success",        
							allowOutsideClick: false,                                                                        
							}).then(function(){
								getBackOrders()
							})
					} else {
						quitarcargando()
						swal(
							'Error',
							'Error al guardar el Pago',
							'error'
						)
					}
				},
				error : function (returndata) {
					swal(
						'Error',
						'Error',
						'error'
					)
				},
			});
		
		},
    },
})

const back = new Vue({
	el: '#app',
	components: {
        vuejsDatepicker
    },
	data: {
		es: vdp_translation_es.js,
        showModal: false,
        row: {},

	},
	methods:{
        modal(){
            this.showModal = true
        },
		facturaServicios(){
			$("#facturaServicios").modal("show");
		},
		addPago(row){
			if(this.pagoslist.map((el) => el.id_fact_prov).indexOf(row.id_fact_prov)>=0)
			{
				swal("Atencion", "Esta factura ya fue agregada","info")
				return
			}
			
			pago.pagoslist.push(row)
		},
		deleteRow:function(index){        
            this.pagoslist.splice(index,1);
        },
		savePago(e){
			agregarcargando()
			e.preventDefault()
			if (parseFloat(this.totalPago) <=0 || !this.fechaPago || !this.save) {
				quitarcargando()
				swal(
					'Error',
					'Revise el formulario',
					'error'
				)
				return
			}
			let formData = new FormData($('#formPago')[0]); 
			formData.append('fechaPago', moment(pago.fechaPago).format('YYYY-MM-DD'))
			formData.append('total', pago.totalPago)
			formData.append('pagos', JSON.stringify(this.pagoslist))


			/* for(let pair of formData.entries()) {
				console.log(pair[0]+ ', '+ pair[1]); 
			}  */
			$.ajax({
				url: base_url("index.php/Importaciones/FacturaProveedores/storePago"),
				type: 'POST',
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				success: function (returndata) {
					res = JSON.parse(returndata)
					console.log(res);
					if (res.status == true) {
						quitarcargando()
						swal({
							title: "Registrado!",
							text: "El pago se asoció con éxito",
							type: "success",        
							allowOutsideClick: false,                                                                        
							}).then(function(){
								agregarcargando()
								location.reload();
							})
					} else {
						quitarcargando()
						swal(
							'Error',
							'Error al guardar el Pago',
							'error'
						)
					}
				},
				error : function (returndata) {
					swal(
						'Error',
						'Error',
						'error'
					)
				},
			});
		},
		cancel(e){
			e.preventDefault()
			console.log('cancelar'); return 
			window.location.href=base_url("index.php/Importaciones/Pedidos");
		},
		customFormatter(date) {
			return moment(date).format('D MMMM  YYYY');
		},
		getTotalPago:function(){
            let total=0
            $.each(this.pagoslist,function(index,value){
                total+=parseFloat(value.monto);
            })
			this.totalPago=total.toFixed(2)
			console.log(this.totalPago);
			return this.totalPago;
			
        },

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