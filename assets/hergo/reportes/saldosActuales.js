let today = moment().format('DD-MM-YYYY')
$(document).ready(function(){
    retornarSaldosActuales();
    base_url('index.php/Reportes/pruebaExcel')
}) 
$(document).on("click", "#excel", function () {
    console.log('object');
    let excel = base_url("reportes/saldosExcel");
    location.href = (excel);
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
		url: base_url('index.php/Reportes/mostrarSaldos'),
		dataType: "json",
	}).done(function (res) {
		table = $('#tablaSaldosActuales').DataTable({
			data: res,
			destroy: true,
			dom: 'Bfrtip',
			responsive: true,			
			lengthMenu: [
				[5, 10, 100, -1],
				['5 filas','10 filas','100 filas', 'Todo']
			],
			pageLength: 100,
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
					title: 'Codigo',
					className: 'text-center',
                },
                {   
                    data: 'url',            
                    title: 'Imagen',
                    searchable: false,
                    render: mostrarimagen
                },
                {
					data: 'descripcion',
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
					data: 'cpp',
					title: 'CPP',
                    className: 'text-right',
                    searchable: false,
                    visible:false,
                    render: numberDecimal
                },
                 {
					data: 'precio',
					title: 'Precio',
                    className: 'text-right',
                    searchable: false,
                    visible: false,
                    render: numberDecimal
                },
                {
					data: 'laPaz',
					title: 'La Paz',
                    className: 'text-right',
                    searchable: false,
                    render: numberDecimal
                },
                {
					data: 'reserva',
					title: 'Reserva LP',
                    className: 'text-right',
                    searchable: false,
                    render: numberDecimal
                },
                {
					data: 'elAlto',
					title: 'El Alto',
                    className: 'text-right',
                    searchable: false,
                    render: numberDecimal
                },
                {
					data: 'potosi',
					title: 'Potosí',
                    className: 'text-right',
                    searchable: false,
                    render: numberDecimal
                },
                {
					data: 'santacruz',
					title: 'Santa Cruz',
                    className: 'text-right',
                    searchable: false,
                    render: numberDecimal
                },
                {
					data: 'reserva_scz',
					title: 'Reserva SCZ',
                    className: 'text-right',
                    searchable: false,
                    render: numberDecimal
                },
                {
					data: 'pendienteAprobar',
					title: 'Por Aprobar',
                    className: 'text-right',
                    searchable: false,
                    render: numberDecimal
                },
                {
					data: 'subTotal',
					title: 'Total',
                    className: 'text-right',
                    searchable: false,
                    render: numberDecimal
                },
                {
					data: 'pasbol',
					title: 'PasBol',
                    className: 'text-right',
                    searchable: false,
                    visible: false,
                    render: numberDecimal
                },
                {
					data: 'total',
					title: 'Total General',
                    className: 'text-right',
                    searchable: false,
                    visible: false,
                    render: numberDecimal
                },
                {
					data: 'backOrder',
					title: 'BackOrder',
                    className: 'text-right',
                    searchable: false,
                    render: numberDecimal
                },
                {
					data: 'estadoPedido',
					title: 'Detalle BackOrder',
                    className: 'text-left',
                    width: '20%'
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
					title: 'Saldos Articulos al ' + today,
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
/*function retornarSaldosActuales2()
{   
    agregarcargando();
    $.ajax({
        type:"POST",
        url: base_url('index.php/Reportes/mostrarSaldos'), //******controlador
        dataType: "json",
    }).done(function(res){
    	quitarcargando();
        $("#tablaSaldosActuales").bootstrapTable('destroy');
        $("#tablaSaldosActuales").bootstrapTable({            ////********cambiar nombre tabla viata

                data:res,    
                    striped:true,
                    search:true,
                    filter:true,
                    showColumns: true,
                    showToggle:true,
                    stickyHeader: true,
                    stickyHeaderOffsetY: '50px',
                columns:
                [
                    {   
                        field: 'id',            
                        title: 'ID',
                        sortable:true,
                        align: 'center',
                        visible:false,
                    },
                   {   
                        field: 'codigo',            
                        title: 'Codigo',
                        sortable:true,
                        align: 'center',
                    },
                    {   
                        field: 'url',            
                        title: 'Imagen',
                        searchable: false,
                        
                        formatter: mostrarimagen
                    },
                    {   
                        field: 'descripcion',            
                        title: 'Descripción',
                        sortable:true,
                        searchable: true,
                    },
                    {   
                        field: 'uni',            
                        title: 'Unidad',
                        sortable:true,
                        searchable: false,
                        align: 'center'
                    },
                    {   
                        field: 'cpp',            
                        title: 'CPP',
                        sortable:true,
                        align: 'right',
                        searchable: false,
                        visible: false,
                        formatter: operateFormatter3
                    },
                    {   
                        field: 'laPaz',            
                        title: 'La Paz',
                        sortable:true,
                        align: 'right',
                        searchable: false,
                        formatter: operateFormatter3
                    },
                    {   
                        field: 'elAlto',            
                        title: 'El Alto',
                        align: 'right',
                        sortable:true,
                        searchable: false,
                        formatter: operateFormatter3
                    },
                    {   
                        field: 'potosi',            
                        title: 'Potosí',
                        align: 'right',
                        sortable:true,
                        searchable: false,
                        formatter: operateFormatter3
                    },
                    {   
                        field: 'santacruz',            
                        title: 'Santa Cruz',
                        align: 'right',
                        sortable:true,
                        searchable: false,
                        formatter: operateFormatter3
                    },
                    {   
                        field: '',            
                        title: 'Total',
                        sortable:true,
                        formatter: total,
                        searchable: false,
                        align: 'right',
                    },
                    {   
                        field: 'backOrder',            
                        title: 'BackOrder',
                        align: 'right',
                        sortable:true,
                        searchable: false,
                        //visible: false,
                        formatter: operateFormatter3
                    },
                    {   
                        field: 'recepcion',            
                        title: 'LLega el:',
                        sortable:true,
                        align: 'center',
                        formatter: formato_fecha,
                        visible: false
                    },
                    {   
                        field: 'estado',            
                        title: 'Estado',
                        align: 'right',
                        visible: false
                    }
                    
                ]
            });
    }).fail(function( jqxhr, textStatus, error ) {
    var err = textStatus + ", " + error;
    console.log( "Request Failed: " + err );
    });
 }*/