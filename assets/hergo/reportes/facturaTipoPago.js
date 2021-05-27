let ini = moment().subtract(0, 'week').format('YYYY-MM-DD')
let fin = moment().format('YYYY-MM-DD')
$(document).ready(function () {
	$.fn.dataTable.ext.errMode = 'none';
	let start = moment().subtract(1, 'week').startOf('week')
	let end = moment()
	dataPicker(start, end)
	getFacTipoPago()
	$('#reportrange').on('apply.daterangepicker', function (ev, picker) {
		getFacTipoPago()
	});
})
function getFacTipoPago() {
    //agregarcargando()
    alm = $("#almacen_filtro").val()
    console.log(ini);
    console.log(fin);
    //return false
	$.ajax({
		type: "POST",
		url: base_url('index.php/reports/FacturaTipoPago/getFacturaTipoPago'),
		dataType: "json",
		data: {
						ini:ini,
						fin:fin,
						alm:alm
					},
	}).done(function (res) {
		table = $('#table').DataTable({
			data: res,
			destroy: true,
			dom: 'Bfrtip',
			responsive: true,			
			lengthMenu: [
				[5, 10, -1],
				['5 filas','10 filas', 'Todo']
			],
			pageLength: 5,
			columns: [
				{
					data: 'id',
					title: 'id',
					className: 'text-center',
				},
				{
					data: 'fechaFac',
					title: 'FECHA',
					className: 'text-center',
					render: formato_fecha,
				},
				{
					data: 'nFactura',
					title: 'Nº FACTURA',
					className: 'text-center',
				},
				{
					data: 'cliente',
					title: 'PROVEEDOR',
				},
				{
					data: 'efectivo',
					title: 'EFECTIVO',
					className: 'text-right',
					sorting: false,
					render: numberDecimal
				},
				{
					data: 'transf',
					title: 'TRANSFERENCIAS',
					className: 'text-right',
					sorting: false,
					render: numberDecimal
				},
				{
					data: 'cheque',
					title: 'CHEQUE',
					className: 'text-right',
					sorting: false,
					render: numberDecimal
				},
				{
					data: 'otros',
					title: 'OTROS',
					className: 'text-right',
					sorting: false,
					render: numberDecimal
				},
				{
					data: 'autor',
					title: 'FACTURADO POR',
					className: 'text-right',
					sorting: false,
					visible: false
				},
				{
					data: null,
					title: '',
					width: '120px',
					className: 'text-center',
					//render: button
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