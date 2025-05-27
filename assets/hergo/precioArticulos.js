let today = moment().format('DD-MM-YYYY')
$(document).ready(function(){
    retornarSaldosActuales();
	/* $('#tabla').on( 'click', 'tr', function () {
		//console.log(this);
        $(this).toggleClass('selected');
    } ); */
 
    $('#button').click( function () {
		datosSelect = table.rows('.selected').data().toArray()
		ids = datosSelect.map(function(item){
			return item.id
		})
		console.log(ids);
    } );
    //base_url('index.php/Reportes/pruebaExcel')
}) 
$(document).on("click",".imagenminiatura",function(){    
    rutaimagen=$(this).attr('src')
    console.table(rutaimagen);
    var imagen='<img class="maximizada" src="'+rutaimagen+'">'
    $("#imagen_max").html(imagen)
    $("#prev_imagen").modal("show");
})
function retornarSaldosActuales() {
	agregarcargando()
	$.ajax({
		type: "POST",
		url: base_url('index.php/PrecioArticulos/showPrecioArticulos'),
		dataType: "json",
	}).done(function (res) {
		table = $('#tabla').DataTable({
			data: res,
			destroy: true,
			dom: 'Bfrtip',
			responsive: true,			
			lengthMenu: [
				[5, 10, 100, -1],
				['5 filas','10 filas','100 filas', 'Todo']
			],
			pageLength: 50,
			columns: [
				{
					data: 'id',
					title: 'ID',
                    className: 'text-center',
                    searchable: false,
                    visible:false
                },
                {
					data: 'codigo',
					title: 'Còdigo',
					className: 'text-center',
                },
                /* {   
                    data: 'url',            
                    title: 'Imagen',
                    searchable: false,
                    render: mostrarimagen
                }, */
                {
					data: 'descrip',
					title: 'Descripción',
                    className: 'text-left',
                    width: '30%'
                },
                {
					data: 'uni',
                    title: 'Unidad',
                    sorting: false,
                    searchable: false,
					className: 'text-center',
                },
                {
					data: 'precioDol',
					title: 'Precio $U$',
                    className: 'text-right',
                    searchable: false,
                    render: numberDecimal
                },
                {
					data: 'precioBob',
					title: 'Precio BOB',
                    className: 'text-right',
                    searchable: false,
                    render: numberDecimal
                },
                {
					data: 'sugerido',
					title: 'Sugerido',
                    className: 'text-right',
                    searchable: false,
                    render: numberDecimal
                },
                {
					data: 'costo',
					title: 'Costo',
                    className: 'text-right',
                    searchable: false,
                    render: numberDecimal
                },
                {
					data: 'porcentaje',
					title: '%',
                    className: 'text-right',
                    searchable: false,
                    render: numberDecimal
                },
				{
					data: 'autor',
					title: 'Autor',
                    className: 'text-right',
                    searchable: false,
					visible:false
                },
				{
					data: 'updatedPrecio_at',
					title: 'Fecha',
                    className: 'text-right',
                    searchable: false,
					visible:false,
					render:formato_fecha_corta
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
					titleAttr: 'Copiar',
					//header: false,
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
					title: 'Lista de Precios ' + today,
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
						retornarSaldosActuales()
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
								retornarSaldosActuales()
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
	showModal(row)

})
function showModal(row) {
	modal.updatePrecio(row)
}
function myFunction() {
	var x = document.getElementById("costo");
	console.log('object');
  }
function operateFormatter3(value, row, index) {
    if (value==='-') {
        return '-'
    } else {
    num=Math.round(value * 100) / 100
    num=num.toFixed(2);
    return (formatNumber.new(num));
    }
}
function total(value, row, index) {
    ea = row.elAlto == '-' ? 0 : parseFloat(row.elAlto) 
    lp = row.laPaz == '-' ? 0 : parseFloat(row.laPaz)
    sc = row.santacruz == '-' ? 0 : parseFloat(row.santacruz)
    pts = row.potosi == '-' ? 0 : parseFloat(row.potosi)
    $ret = ea+lp+sc+pts
    $ret = $ret.toFixed(2)
    return ($ret)
  }
function mostrarimagen(value, row, index)
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
        ruta="/assets/img_articulos/"+value
    }

    imagen = '<div class="contimg"><img src="'+base_url(ruta)+'" class="'+clase+'"></div>'
    return [imagen].join('')
}
const modal = new Vue({
	el: '#app',
	data: {
		id: 0,
		codigo:'',
		descrip:'',
		uni:'',
		subtitle:'',
		porcentaje:0,
		sugeridoDolares:0,
		sugeridoBOB:0,
		precioBol:0,
		precioDolares:0,
		antPrecioDolares:0,
		antPrecioBol:0,
		antCosto:0,
		antPorcentaje: 0,
		sugerido:0,
		costo: 0,
	},
	methods:{
		updatePrecio(row){
			modal.id = row.id
			modal.codigo = row.codigo
			modal.descrip = row.descrip
			modal.uni = row.uni
			modal.antPrecioBol = row.precioBob || 0
			modal.antPrecioDolares = row.precioDol || 0
			modal.antCosto = row.costo || 0
			modal.antPorcentaje = row.porcentaje || 0
			modal.subtitle = `${row.codigo} || ${row.descrip} || ${row.uni}`
			modal.costo = row.costo || 0
			modal.porcentaje = row.porcentaje || 0
			modal.sugeridoDolares = Math.round(((row.costo || 0) / (0.84 - ((row.porcentaje || 0)/100))) * 100) / 100
			modal.sugeridoBOB = Math.round(modal.sugeridoDolares * 6.96 * 100) / 100
			modal.precioBol = row.precioBob || 0
			modal.precioDolares = row.precioDol || 0
			$("#modalPrecio").modal("show");
		},
		precioSugerido(){
			modal.sugeridoDolares = Math.round((modal.costo / (0.84 - (modal.porcentaje/100))) * 100) / 100
			modal.sugeridoBOB = Math.round(modal.sugeridoDolares * 6.96 * 100) / 100
		},
		editar(e){
			agregarcargando()
			e.preventDefault()
			$.ajax({
				url: base_url("index.php/PrecioArticulos/update"),
				type: 'POST',
				data: {
					id: modal.id,
					costo: modal.costo === '' ? '0' : modal.costo,
					porcentaje: modal.porcentaje === '' ? '0' : modal.porcentaje,
					precioBol: modal.precioBol === '' ? '0' : modal.precioBol,
					precioDolares: modal.precioDolares === '' ? '0' : modal.precioDolares,
					codigo: modal.codigo,
					descrip: modal.descrip,
					uni:modal.uni,
					antPrecioBol: modal.antPrecioBol,
					antPrecioDolares: modal.antPrecioDolares
				},
				cache: false,
				//contentType: false,
				//processData: false,
				success: function (res) {
					retornarSaldosActuales()
				},
				error : function (returndata) {
					swal(
						'Error',
						'Error',
						'error'
					)
				},
			});



			$("#modalPrecio").modal("hide");
		}


	},
	filters:{
		moneda:function(value){
			num=Math.round(value * 100) / 100
			num=num.toFixed(2);
			return numeral(num).format('0,0.00');            
		}, 
	}
})