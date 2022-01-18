let ini = moment().subtract(10, 'year').startOf('year').format('YYYY-MM-DD')
let fin = moment().subtract(0, 'year').endOf('year').format('YYYY-MM-DD')
$(document).ready(function () {
    console.log(ini);
	$.fn.dataTable.ext.errMode = 'none';
	dataPicker(ini)
	getFPP()
	$('#reportrange').on('apply.daterangepicker', function (ev, picker) {
		getFPP()
	});
})

function getFPP() {
	agregarcargando()
	$.ajax({
		type: "POST",
		url: base_url('index.php/reports/FacturasPendientesPago/show'),
		dataType: "json",
		data: {
                ini:ini,
                fin:fin,
                alm:fpp.almacen,
				tipoEgreso:fpp.tipoEgreso
		},
	}).done(function (res) {
		//console.log(res);
		table = $('#table').DataTable({
			data: res,
			destroy: true,
			dom: 'Bfrtip',
			responsive: true,
			lengthMenu: [
				[10, 25, 50, -1],
				['10 filas', '25 filas', '50 filas', 'Todo']
			],
			pageLength: -1,
            createdRow: function( row, data, dataIndex ) {
                if (data['idFactura'] == null && data['cliente'] != "TOTAL GENERAL" ) {
                    $(row).addClass( 'subTotalesBG' );
                } else if ( data['cliente'] == "TOTAL GENERAL") {
                    $(row).addClass( 'totalesBG' );
                } /* else if ( data['estado'] == "VENCIDA") {
                    $(row).addClass( 'styleRed' );
                }  */
            },
			columns: [
				{
					data: 'idFactura',
					title: 'idFactura',
					className: 'text-center',
					sorting: false,
                    visible: false,
				},
				{
					data: 'idEgresos',
					title: 'idEgresos',
					className: 'text-center',
					sorting: false,
                    visible: false
				},

                {
					data: 'almacen',
					title: 'ALMACEN',
					className: 'text-center',
                    visible: false
				},
				{
					data: 'egreso',
					title: 'Egreso',
					className: 'text-center',
					sorting: false,
                    visible: true,
					render:linkEgreso
				},
				{
					data: 'nFactura',
					title: 'Nº FAC',
					className: 'text-center',
					render:linkFactura
				},
				{
					data: 'fechaFac',
					title: 'FECHA',
					className: 'text-center',
					render: formato_fecha,
				},
				{
					data: 'cliente',
					title: 'CLIENTE',
				},
				{
					data: 'total',
					title: 'CRÉDITO',
					className: 'text-right',
					render: numberDecimal
				},
                {
					data: 'montoPagado',
					title: 'ABONO',
					className: 'text-right',
					render: numberDecimal
				},
                {
					data: 'saldo',
					title: 'SALDO',
					className: 'text-right',
					render: numberDecimal
				},
                {
					data: 'diasCredito',
					title: 'DIAS CREDITO',
					className: 'text-center',
                    sorting: false,
				},
				{
					data: 'vendedor',
					title: 'VENDEDOR',
					className: 'text-right',
					sorting: false,
				},
				{
					data: 'fechaVencimiento',
					title: 'VENCIMIENTO',
					className: 'text-center',
					render: formato_fecha,
				},
                {
					data: 'estado',
					title: 'ESTADO',
					className: 'text-right',
					sorting: false,
				},
				/* {
					data: null,
					title: '',
					//width: '120px',
					className: 'text-center',
					sorting: false,
					render: buttons
				}, */
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
					header: true,
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
					title: 'Facturas Pendientes de Pago',
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
								getFPP()
							}
						},

					]
				},
				{
					text: '<i class="fas fa-sync" aria-hidden="true" style="font-size:18px;"></i>',
					action: function (e, dt, node, config) {
						//table.state.clear()
						getFPP()
					}
				},

			],
			order: [],
			fixedHeader: {
				header: true,
				footer: true
			},
			//paging: false,
			responsive: true,
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
			aoColumnDefs: [
			 { "bSearchable": false, "aTargets": [ 0,1 ] }
		   ] 
		});
		//table.columns.adjust();
		quitarcargando();
	}).fail(function (jqxhr, textStatus, error) {
		let err = textStatus + ", " + error;
		console.log("Request Failed: " + err);
	});
	
}
function linkFactura(value, row, index) {
	let print = base_url("pdf/Factura/index/") + index.idFactura;
	return `<a href=${print} target="_blank">${index.nFactura}</a>`
}
function linkEgreso(value, row, index) {
	let print = base_url("pdf/Egresos/index/") + index.idEgresos;
	return `<a href=${print} target="_blank">${index.egreso}</a>`
}
function buttons (data, type, row) {
	if (row.id>0) {
		return `
			<button type="button" class="btn btn-default add">
				<span class="fa fa-plus" aria-hidden="true">
				</span>
			</button>
			<button type="button" class="btn btn-default see">
				<span class="fa fa-search" aria-hidden="true">
				</span>
			</button>
		`
	}
}
$(document).on("click", "button.add", function () {
    let row = getRow(table, this)
	fpp.addNota(row)
})
$(document).on("click", "button.see", function () {
    let row = getRow(table, this)

})
const fpp = new Vue({
	el: '#app',
	data: {
        almacen:document.getElementById("idAlmacenUsuario").value,
		tipoEgreso:7,
        almacenes: [
            { alm: 'CENTRAL HERGO', value: '1' },
            { alm: 'DEPOSITO EL ALTO', value: '2' },
            { alm: 'POTOSI', value: '3' },
            { alm: 'SANTA CRUZ', value: '4' },
            { alm: 'TODOS', value: '' },
        ],
		tiposEgreso: [
            { tipo: 'NOTA DE ENTREGA', value: '7' },
            { tipo: 'VENTA CAJA', value: '6' },
            { tipo: 'TODOS', value: '' },
        ],
		id:0,
		nota:'',
		disabled: document.getElementById("nacional").value == '' ? true : false,
	},
	mounted() {
		if (localStorage.firma) {
		  this.firma = localStorage.firma;
		}
	},
	methods:{
		onChangeFilter(){
            getFPP()
        },
		idNacional(){
			if (isNacional == "") {
				console.log('no es nacional');
			}
		},
		addNota(row){
			console.log(row);
			fpp.id = row.id
			$("#addNotaModal").modal("show")
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
	watch: {
		firma() {
			localStorage.firma = this.firma
		}
	}
})
