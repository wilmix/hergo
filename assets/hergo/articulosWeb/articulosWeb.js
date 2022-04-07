$(document).ready(function () {
	$.fn.dataTable.ext.errMode = 'none';
	getItems()
})

function getItems() {
	//agregarcargando()
	$.ajax({
		type: "POST",
		url: base_url('web/ArticulosWeb/getItems'),
		dataType: "json",
		data: {
               
		},
	}).done(function (res) {
		table = $('#web').DataTable({
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
					data: 'articulo_id_sis',
					title: 'articulo_id',
					className: 'text-center',
                    visible: false
				},
                {
					data: 'codigo_sis',
					title: 'Codigo Sis',
					className: 'text-center',
				},
                {
					data: 'descripcion_sis',
					title: 'Descripción Sis',
					className: 'text-center',
				},
				{
					data: 'titulo',
					title: 'Título',
					className: 'text-right',
				},
				{
					data: 'descripcion',
					title: 'Descripción',
                    className: 'text-right',
				},
				{
					data: 'n1',
					title: 'Nivel 1',
				},
                {
					data: 'n2',
					title: 'Nivel 2',
				},
                {
					data: 'n3',
					title: 'Nivel 3',
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
                    data: 'img_sis',
                    title: 'Imagen Sis',
                    className: 'text-left',
                    render: showImageSis,
                },
                {
                    data: 'img',
                    title: 'Imagen',
                    className: 'text-left',
                    render: showImage,
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
			language: datatableLangage,
		});
		quitarcargando();
	}).fail(function (jqxhr, textStatus, error) {
		let err = textStatus + ", " + error;
		console.log("Request Failed: " + err);
	});
}
function showImage(value, row, index, route)
{
    let ruta=""
    let imagen=""
    if((value=="")||(value==null))
    {
        ruta="/assets/img_articulos/hergo.jpg"
        clase=""
    }
    else
    {
        clase="imagenminiatura"
        ruta="https://images.hergo.app/web/levels/"+value
    }

    imagen = '<div class="contimg"><img src="'+ruta+'" class="'+clase+'"></div>'
    return [imagen].join('')
}
function showImageSis(value, row, index, route)
{
    let ruta=""
    let imagen=""
    if((value=="")||(value==null))
    {
        ruta="/assets/img_articulos/hergo.jpg"
        clase=""
    }
    else
    {
        clase="imagenminiatura"
        ruta=base_url("/assets/img_articulos/")+value
    }

    imagen = '<div class="contimg"><img src="'+ruta+'" class="'+clase+'"></div>'
    return [imagen].join('')
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
})

const web = new Vue({
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