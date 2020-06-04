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
						condicion:'ordenProcess'

					},
	}).done(function (res) {
		table = $('#tableOC').DataTable({
			data: res,
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
					data: 'nOrden',
					title: 'Nº',
					className: 'text-center',
				},
				{
					data: 'fechaOC',
					title: 'FECHA',
					className: 'text-center',
					render: formato_fecha,
				},
				{
					data: 'proveedor',
					title: 'PROVEEDOR',
				},
				{
					data: 'condicion',
					title: 'CONDICION',
					className: 'text-center',
				},
				{
					data: 'formaPago',
					title: 'FORMA DE PAGO',
				},
				{
					data: 'formaEnvio',
					title: 'FORMA ENVIO',
				},
				{
					data: 'total$',
					title: 'TOTAL $U$',
					className: 'text-right',
					sorting: false,
					render: numberDecimal
				},
				{
					data: 'autor',
					title: 'CREADO POR',
					className: 'text-right',
					sorting: false,
					visible: false
				},
				{
					data: 'created_at_orden',
					title: 'CREADO EN',
					className: 'text-center',
					render: formato_fecha_corta,
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
function button (data, type, row) {
    return `
        <button type="button" class="btn btn-default print">
            <span class="fa fa-print" aria-hidden="true">
            </span>
        </button>
    `
    }