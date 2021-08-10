let ini = moment().subtract(0, 'year').startOf('year').format('YYYY-MM-DD')
let fin = moment().subtract(0, 'year').endOf('year').format('YYYY-MM-DD')
$(document).ready(function () {
	$.fn.dataTable.ext.errMode = 'none';
	dataPicker()
	getProforma()
	$('#reportrange').on('apply.daterangepicker', function (ev, picker) {
		getProforma()
	});
})

function getProforma() {
	agregarcargando()
	$.ajax({
		type: "POST",
		url: base_url('index.php/Proforma/getProformas'),
		dataType: "json",
		data: {
                ini:ini,
                fin:fin,
                alm:pro.almacen
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
			pageLength: 10,
			columns: [
				{
					data: 'id',
					title: 'id',
					className: 'text-center',
                    visible: false
				},
				{
					data: 'num',
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
					data: 'clienteDatos',
					title: 'CLIENTE',
				},
				{
					data: 'tipo',
					title: 'TIPO',
				},
				{
					data: 'total',
					title: 'TOTAL',
					sorting: false,
					className: 'text-right',
					render: numberDecimal
				},
                {
					data: 'sigla',
					title: 'MONEDA',
					className: 'text-center',
				},
				{
					data: 'autor',
					title: 'AUTOR',
					className: 'text-right',
					sorting: false,
				},
				{
					data: 'created_at',
					title: 'CREADO EN',
					className: 'text-center',
					render: formato_fecha_corta,
					visible: false
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
						getProforma()
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
								getProforma()
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
	<button type="button" class="btn btn-default print">
		<span class="fa fa-print" aria-hidden="true">
		</span>
	</button>
	<button type="button" class="btn btn-default edit">
		<span class="fa fa-pencil" aria-hidden="true">
		</span>
	</button>
`
}


$(document).on("click", "button.print", function () {

    let row = getRow(table, this)
	let dist = 10
	let imgSize = 12
	let firma = pro.firma ? 1 : 0
	let print = base_url("pdf/Proforma/index/") + `${row.id}/${dist}/${imgSize}/${firma}`
	window.open(print);
})
$(document).on("click", "button.edit", function () {

    let row = getRow(table, this)
	let print = base_url("Proforma/formProforma/") + row.id;
	window.open(print);
})

const pro = new Vue({
	el: '#app',
	data: {
        almacen:document.getElementById("idAlmacenUsuario").value,
        almacenes: [
            { alm: 'CENTRAL HERGO', value: '1' },
            { alm: 'DEPOSITO EL ALTO', value: '2' },
            { alm: 'POTOSI', value: '3' },
            { alm: 'SANTA CRUZ', value: '4' },
        ],
		id:0,
		firma:false,
		disabled: document.getElementById("nacional").value == '' ? true : false,
	},
	mounted() {
		if (localStorage.firma) {
		  this.firma = localStorage.firma;
		}
	},
	methods:{
		onChangeAlm(){
            getProforma()
        },
		idNacional(){
			if (isNacional == "") {
				console.log('no es nacional');
			}
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