let ini = moment().subtract(0, 'year').startOf('year').format('YYYY-MM-DD')
let fin = moment().subtract(0, 'year').endOf('year').format('YYYY-MM-DD')
$(document).ready(function () {
	$.fn.dataTable.ext.errMode = 'none';
	dataPicker()
	getEstadoCuentas()
	$('#reportrange').on('apply.daterangepicker', function (ev, picker) {
		getEstadoCuentas()
	});
})
$(document).on("change", "#estadoFiltro", function () {
    getEstadoCuentas();
})
$(document).on("change", "#pedServFilter", function () {
    getEstadoCuentas()
})
function getEstadoCuentas() {
	let pedServ = alm = $("#pedServFilter").val()
	let estado = $("#estadoFiltro").val()
	let titulo  = pedServ == 'servicios' ? 'ESTADO DE CUENTAS SERVICIOS': 'ESTADO DE CUENTAS'
	$("#titulo").text(titulo)
	agregarcargando()
	$.ajax({
		type: "POST",
		url: base_url('index.php/Importaciones/EstadoCuentas/getEstadoCuentas'),
		dataType: "json",
		data: { condicion: estado,
				pedServ : pedServ },
	}).done(function (res) {
        res.forEach(element => element.id_fact_prov ? element.estado = element.estadoFac : element.estado = element.estadoOrden);
        console.log(res);
		table = $('#table').DataTable({
			data: res,
			destroy: true,
			dom: 'Bfrtip',
			lengthMenu: [
				[5, 10, -1],
				['5 filas','10 filas', 'Todo']
			],
			createdRow: function( row, res, dataIndex ) {
				if ( res.estado == 'PAGADA') {
				  	$(row).addClass( 'styleGreen' );
				} else if (res.estado == 'VENCIDA') {
					$(row).addClass( 'styleRed' );
				} else if (res.estado == 'PARCIAL' &&  moment(res.fechaVencimiento).format('YYYY-MM-DD') < moment().format('YYYY-MM-DD')) {
					$(row).addClass( 'styleRed' );
				}
			},
			pageLength: 15,
			columns: [
				{
					data: 'orden',
					title: 'Nº',
					className: 'text-center',
				},
				{
					data: pedServ == 'pedidos' ? 'nombreproveedor' : 'glosa',
					title: pedServ == 'pedidos' ? 'PROVEEDOR' : 'PEDIDOS',
					
				},
				{
					data: pedServ == 'pedidos' ? '' : 'transporte',
					title: pedServ == 'pedidos' ? '' : 'TRASNPORTE',
					visible:pedServ == 'pedidos' ? false : true,
                },
				{
					data: 'credito',
					title: 'CRÉDITO',
					className: 'text-center',
                },
                {
					data: 'nFactProv',
					title: 'N° FACTURA',
					className: 'text-center',
                },
                {
					data: 'fechaEmision',
					title: 'FECHA EMISIÓN',
					className: 'text-center',
					render: formato_fecha,
                },
                {
					data: 'monto',
					title: 'MONTO',
					className: 'text-right',
					render: numberDecimal,
                },
                {
					data: 'fechaVencimiento',
					title: 'VENCIMIENTO',
					className: 'text-center',
					render: formato_fecha,
				},
				{
					data: 'fechaPago',
					title: 'FECHA PAGO',
					className: 'text-center',
					render: formato_fecha,
					visible:false
                },
                {
					data: 'pagoCuenta',
					title: 'PAGO A CUENTA',
					className: 'text-right',
					render: numberDecimal,
				},
				{
					data: 'saldo',
					title: 'SALDO',
					className: 'text-right',
					render: numberDecimal,
					sorting: false
				},
				{
					data: 'estado',
					title: 'ESTADO',
					className: 'text-center',
					//sorting: false,
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
					title: 'Estado de Cuentas ',
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
						getEstadoCuentas()
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
								getEstadoCuentas()
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
function button (data, type, row) {
	if (row.estadoOrden == 'FACTURADA') {
		return `
        <button type="button" class="btn btn-default print ">
            <span class="fa fa-print" aria-hidden="true">
            </span>
		</button>
		<button type="button" class="btn btn-default edit">
            <span class="fa fa-pencil" aria-hidden="true">
            </span>
		</button>
		<button type="button" class="btn btn-default asociarFac" disabled>
            <span class="fa fa-file-text-o" aria-hidden="true">
            </span>
		</button>
    	`
	} else {
		return `
        <button type="button" class="btn btn-default print">
            <span class="fa fa-print" aria-hidden="true">
            </span>
		</button>
		<button type="button" class="btn btn-default edit">
            <span class="fa fa-pencil" aria-hidden="true">
            </span>
		</button>
		<button type="button" class="btn btn-default asociarFac">
            <span class="fa fa-file-text-o" aria-hidden="true">
            </span>
		</button>
    `
	}
   
}
$(document).on("click", "button.print", function () {
	let row = table.row( $(this).parents('tr') ).data();
	let print = base_url("pdf/OrdenCompraPDF/index/") + row.id_pedido;
    window.open(print);
})
$(document).on("click", "button.edit", function () {
	let row = table.row( $(this).parents('tr') ).data();
	let edit = base_url("Importaciones/OrdenesCompra/editOrden/") + row.id_pedido;
    window.open(edit);
})
$(document).on("click", "button.asociarFac", function () {
	let row = table.row( $(this).parents('tr') ).data();
	//console.log(row);
	modal.asociarFactura(row.id_pedido, row)
})


/*const modal = new Vue({
	el: '#app',
	components: {
        vuejsDatepicker
    },
	data: {
		id:0,
		id_orden:'',
		n:'',
		fecha: '',
		id_proveedor:'',
        proveedor:'',
		montoOrden:'',
		totalFacturaC:0,
		tiempo_credito:'',
		condicion:'',
		formaEnvio:'',
		url:'',
		n_ordenCompra:'',
		es: vdp_translation_es.js,
	},
	methods:{
		asociarFactura(id, row){
			modal.id_orden = row.id_ordenCompra
			modal.montoOrden = row.saldoAsoFac
			modal.tiempo_credito = row.diasCredito
			modal.proveedor = row.proveedor
			modal.n_ordenCompra = row.nOrden
			modal.condicion = row.condicion
			modal.formaEnvio = row.formaEnvio
			modal.id_proveedor = row.id_proveedor
			$("#asociarFacturaModal").modal("show");
		},
		saveFactura(e){
			agregarcargando()
			e.preventDefault()
			if (this.totalFacturaC<=0 || !this.fecha ) {
				quitarcargando()

				swal(
					'Error',
					'Revise el formulario',
					'error'
				)
				return
			} 

			let formData = new FormData($('#modalAsociarFactura')[0]); 
			formData.append('id_orden',modal.id_orden)
			formData.append('id_proveedor',modal.id_proveedor)
			formData.append('fecha', moment(modal.fecha).format('YYYY-MM-DD'))

			/* for(let pair of formData.entries()) {
				console.log(pair[0]+ ', '+ pair[1]); 
			} */
			/*$.ajax({
				url: base_url("index.php/Importaciones/OrdenesCompra/storeAsociarFactura"),
				type: 'POST',
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				success: function (returndata) {
					res = JSON.parse(returndata)
					if (returndata != 'false') {
						quitarcargando()
						swal({
							title: "Registrado!",
							text: "El factura se asoció con éxito",
							type: "success",        
							allowOutsideClick: false,                                                                        
							}).then(function(){
								$('#modalAsociarFactura').hide();
								//$('#modalAsociarFactura')[0].reset();
								location.reload();
							})
					} else {
						quitarcargando()
						swal(
							'Error',
							'Error al guardar el Factura Comercial',
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
		customFormatter(date) {
			return moment(date).format('D MMMM  YYYY');
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
})*/