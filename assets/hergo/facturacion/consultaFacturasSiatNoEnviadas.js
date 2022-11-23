let ini = moment().subtract(0, 'year').startOf('year').format('YYYY-MM-DD')
let fin = moment().subtract(0, 'year').endOf('year').format('YYYY-MM-DD')
$(document).ready(function () {
	$.fn.dataTable.ext.errMode = 'none';
	dataPicker()
	getData()
	$('#reportrange').on('apply.daterangepicker', function (ev, picker) {
		getData()
	});
	$('#table').on('click', 'tr', function () {
		$(this).toggleClass('selected');
	});
	/* $('#buttonSelected').click(function () {
		let selected = table.rows('.selected').data()
		cufs = []
		selected.map(x =>cufs.push(x.cuf))
		pro.cufs = cufs
    }); */
})


function getData() {
	agregarcargando()
	$.ajax({
		type: "POST",
		url: base_url('index.php/siat/facturacion/Emitir/getFacturasSiatNoEnviadas'),
		dataType: "json",
		data: {
                ini:ini,
                fin:fin,
                alm:pro.almacen
		},
	}).done(function (res) {
        //console.log(res);
		table = $('#table').DataTable({
			data: res,
			select: true,
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
					data: 'idFactura',
					title: 'id',
					className: 'text-center',
                    visible: false
				},
				{
					data: '',
					title: 'TIPO',
					className: 'text-center',
					render: tipoFactura
				},
				{
					data: 'numeroFactura',
					title: 'Nº',
					className: 'text-center',
				},
				{
					data: 'fechaEmision',
					title: 'FECHA SIAT:',
					className: 'text-center',
					//render: formato_fecha_corta,
					//visible: false
				},
                {
					data: 'ClienteNit',
					title: 'NIT',
					width: '10%'
				},
				{
					data: 'ClienteFactura',
					title: 'CLIENTE',
				},
                {
					data: 'cuf',
					title: 'CUF',
					width: '10%',
                    visible: false
				},
				{
					data: 'total',
					title: 'TOTAL',
					sorting: false,
					className: 'text-right',
                    width: '10%',
					render: numberDecimal
				},
                {
					data: 'cufd',
					title: 'CUFD',
					className: 'text-center',
					visible: false
				},
				{
					data: 'emisor',
					title: 'EMITIDO POR:',
					className: 'text-right',
					sorting: false,
					visible: false
				},
                {
					data: 'codigoRecepcion',
					title: 'CODIGO RECEPCIÓN',
                    width: '10%',
				},
                {
                    data:'codigoSucursal',
                    title:"codigoSucursal",
					className: 'text-right',
                    visible: false
                }, 
                {
                    data:'codigoPuntoVenta',
                    title:"codigoPuntoVenta",
					className: 'text-right',
                    visible: false
                },
				{
					data: null,
					title: '',
                    width: '10%',
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
					title: 'Reporte Facturas',
					exportOptions: {
						columns: ':visible'
					},
				},
				{
					text: '<i class="fas fa-sync" aria-hidden="true" style="font-size:18px;"></i>',
					action: function (e, dt, node, config) {
						getData()
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
								getData()
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
			language: datatableLangage

		});
		quitarcargando();
	}).fail(function (jqxhr, textStatus, error) {
		let err = textStatus + ", " + error;
		console.log("Request Failed: " + err);
	});
}

function buttons (data, type, row) {
	let validar = `<button type="button" class="btn btn-default validar">
					<span class="fa fa-check" aria-hidden="true">
					</span>
				</button>`
	let buttons = `${validar}`
	return buttons
}

$(document).on("click", "button.validar", function () {
    let row = getRow(table, this)
	pro.validarEstadoFactura(row)
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
		disabled: document.getElementById("nacional").value == '' ? true : false,
		infoAlmacen: false,
		facturaRow:false,
		codigoPuntoVenta:CPV,
        codigoEvento: '',
        cantidadFacturas:0,
        cufs: [],
        cafc:''//'1018E82642F3D'

	},
	mounted() {
		this.get_codigos()
	},
	methods:{
		onChangeAlm(){
            getData()
        },
		get_codigos(){
			$.ajax({
				type: "POST",
				url: base_url('siat/facturacion/Emitir/getCodigos'),
				dataType: "json",
				data:{
					codigoPuntoVenta : this.codigoPuntoVenta
                }
			}).done(function (res) {
				pro.infoAlmacen = res
				quitarcargando();
			}).fail(function (jqxhr, textStatus, error) {
				let err = textStatus + ", " + error;
				console.log("Request Failed: " + err);
			});
		},
		validarEstadoFactura(row){
            agregarcargando()
            data = {
                codigoPuntoVenta: this.infoAlmacen.codigoPuntoVenta,
                codigoSucursal:this.infoAlmacen.sucursal,
                codigoDocumentoSector: "1",
                codigoEmision: "1",
                cufd: this.infoAlmacen.codigoCufd,
                cuis: this.infoAlmacen.cuis,
                tipoFacturaDocumento: "1",
                cuf: row.cuf
            }
            $.ajax({
				type: "POST",
				url: base_url_siat('verificacionEstadoFactura'),
				dataType: "json",
				data:data
			}).done(function (res) {
				res = res.RespuestaServicioFacturacion
                console.log(res);
                if (res.transaccion) {
                    quitarcargando()
                    swal({
						title: `${res.codigoDescripcion}`,
						html: `La factura esta validada con el SIAT con el siguiente código de recepción
									<br> ${res.codigoRecepcion} 
                                    <br> codigo estado: ${res.codigoEstado} `,
						type: 'success',
						showCancelButton: false,
						allowOutsideClick: false,
					}).then(function (result) {
                        location.reload(); 
					});
                } else {
                    quitarcargando()
                    swal({
                        title: `${res.codigoDescripcion}`,
                        html: `${res.mensajesList.descripcion}`,
                        type: 'error',
                        showCancelButton: false,
                        allowOutsideClick: false,
                    })
                    return
                }
			}).fail(function (jqxhr, textStatus, error) {
                quitarcargando()
				let err = textStatus + ", " + error;
                swal({
                    title: `Error`,
                    html: `Error con la comuniación con el SIAT <br>
                            ${err} <br>
                            Intente de nuevo en un momento.`,
                    type: 'error',
                    showCancelButton: false,
                    allowOutsideClick: false,
                })
				console.log("Request Failed: " + err);
			});
		},
        now(){
			return moment().format('YYYY-MM-DDTHH:mm:ss.SSS');
		},
		modalEmpaquetar(){
            let cufs = []
            let selected = table.rows('.selected').data()
            selected.map(x =>cufs.push(x.cuf))
            this.cufs = cufs
            if (cufs.length == 0) {
                swal({
                    title: 'Error',
                    html: `Seleccione una o más facturas.`,
                    type: 'error',
                    showCancelButton: false,
                    allowOutsideClick: false,
                })
                return
            } else {
				this.cantidadFacturas = cufs.length
			}
            $("#modal").modal("show");
		},
        enviarPaquete(){
            agregarcargando()
            data = {
                codigoPuntoVenta: this.infoAlmacen.codigoPuntoVenta,
                codigoSucursal:this.infoAlmacen.sucursal,
                codigoDocumentoSector: "1",
                codigoEmision: "2",
                cufd: this.infoAlmacen.codigoCufd,
                cuis: this.infoAlmacen.cuis,
                tipoFacturaDocumento: "1",
                fechaEnvio: this.now(),
                cafc: this.cafc,
                codigoEvento: this.codigoEvento,
                cantidadFacturas: this.cantidadFacturas,
                cufs: this.cufs
            }
			$.ajax({
				type: "POST",
				url: base_url_siat('recepcionPaquete'),
				dataType: "json",
				data:data
			}).done(function (res) {
                if (res.transaccion) {
                    quitarcargando()
					msj = ''
                    if (res.mensajesList) {
                        var mensajesList = ''
						if (res.mensajesList.length > 0) {
							Object.entries(res.mensajesList).forEach(element => {
								console.log(element[1].descripcion);
								mensajesList += `<li>${element[1].descripcion}</li>`
							});
						}
                        console.log(mensajesList);
						msj = `Existen las siguientes observaciones: 
									<br> ${mensajesList}
									<br> Código estado: ${res.codigoEstado}`
						
                    } else {
						msj = `Las facturas se empaquetaron y enviaron de manera correcta con el codigo de recepción:
						<br> ${res.codigoRecepcion} 
						<br> Código estado: ${res.codigoEstado}`
					}
					
                    swal({
						title: `${res.codigoDescripcion}`,
						html: msj,
						type: 'success',
						showCancelButton: false,
						allowOutsideClick: false,
					}).then(function (result) {
                        location.reload(); 
					});
                } else {
                    quitarcargando()
                    swal({
                        title: `${res.codigoDescripcion}`,
                        html: `${res.mensajesList.descripcion}`,
                        type: 'error',
                        showCancelButton: false,
                        allowOutsideClick: false,
                    })
                    return
                }
			}).fail(function (jqxhr, textStatus, error) {
				quitarcargando();
				let err = textStatus + ", " + error;
				console.log("Request Failed: " + err);
				swal({
					title: 'Vuelva a intentar',
					text: "Vuelva a intenar error con conexion al SIAT",
					type: 'warning',
					showCancelButton: false,
					allowOutsideClick: false,
				})
			});
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

	}
})