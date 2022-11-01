$(document).on("click", "button.detalle", function () {
    let row = getRow(pendientes, this)
	let pedido = row.clientePedido ? `<b>Pedido:</b> ${row.clientePedido}` : ''
	$('#tituloEgreso').text(`${row.movimiento}`)
	$('#clienteEgreso').html(`<pre><b>Cliente:</b>${row.nombreCliente} <b>Fecha:</b>${formato_fecha(row.fecha)}  <b>Total:</b>${numberDecimal(row.montoTotal)}  ${pedido}</pre>`)
	bill.egreso = row
	bill.porFacturarDetalle(row)
})
$(document).on("click", "button.facturarTodo", function () {
	bill.facturarEgreso()
	
})
$(document).on("click", "button.facturarItem", function () {
	let row = getRow(tableDetalle, this)
	bill.facturarItem(row)
})
function subTotalDetalle(value, row, index) {
    let pu = (Math.round(row.precioUnitario * 100) / 100).toFixed(2)
    let cant = (Math.round(row.cantidad * 100) / 100).toFixed(2)
    return (formato_moneda(pu * cant));
}

Vue.component('vue-ctk-date-time-picker', window['vue-ctk-date-time-picker']);
Vue.component("v-select", VueSelect.VueSelect);
const bill = new Vue({
	el: '#app',
	components: {
		vuejsDatepicker
	},
	data: {
		yourValue:null,
		siatOnline: false,
		tituloFactura: '',
		configuracion:{},
		infoAlmacen:[],
		egreso:[],
		codigoEmision:'1',
		emision:'1',
		tipoFacturaDocumento: '1',
		egresoDetalle:[],
		cabecera:false,
		detalle:[],
		edit:false,
		totalFactura:0,
		validar: false,
		fechaEmision:null,
		es: vdp_translation_es.js,
		moneda: '1',
		monedas_siat:[],
		metodos_pago_siat:[],
		metodo_pago_siat: {
			id: '1',
			label: 'EFECTIVO'
		},
		glosa:'',
		errors:[],
		errorsDetalle :[],
		codigoPuntoVenta: CPV,
		numeroTarjeta:'',
		leyenda:'',
		usuario:'',
		factura_id:0,
		tituloAlmacen: 'ALMACEN',
		numeroFacturaContingencia: '',
		cafc: '1018E82642F3D',
		tipoCambio: null,
		montoTotalMoneda: null
	},
	mounted() {
		this.verificarSiat()
		this.info_factura()
		this.porFacturar(glob_alm_usu)
		this.get_codigos()
	},
	methods:{
		info_factura(){
			$.ajax({
				type: "POST",
				url: base_url('siat/facturacion/Emitir/infoFactura'),
				dataType: "json"
			}).done(function (res) {
				bill.monedas_siat = res.monedas_siat
				bill.metodos_pago_siat = res.metodos_pago_siat
				bill.leyenda =  res.leyenda.descripcionLeyenda
				bill.usuario = res.user.usuario
				quitarcargando();
			}).fail(function (jqxhr, textStatus, error) {
				let err = textStatus + ", " + error;
				console.log("Request Failed: " + err);
			});
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
				bill.infoAlmacen = res
				//bill.cuis = res.cuis
				//bill.codigoControlCUFD = res.codigoControlCufd
				//bill.cufd = res.codigoCufd
				quitarcargando();
			}).fail(function (jqxhr, textStatus, error) {
				let err = textStatus + ", " + error;
				console.log("Request Failed: " + err);
			});
		},
		porFacturar(almacen){
			agregarcargando()			
			$.fn.dataTable.ext.errMode = 'none';
			$.ajax({
				type: "POST",
				url: base_url('siat/facturacion/Emitir/pendientesFacturar'),
				dataType: "json",
				data:{
					almacen: almacen
				}
			}).done(function (res) {
				//console.info(res[0].almacen);
				bill.tituloAlmacen = res[0].almacen
				pendientes = $('#pendientesFacturar').DataTable({
					data: res,
					destroy: true,
                    scrollY: '200px',
					responsive: true,
                    scrollCollapse: true,
                    paging: false,
					dom: 'Bfrtip',
					/* lengthMenu: [
						[10, 25, 50, -1],
						['10 filas', '25 filas', '50 filas', 'Todo']
					], */
					pageLength: -1,
					columns: [
                       
						{
							data: 'cliente_id',
							title: 'cliente_id',
							className: 'text-center',
							visible: false
						},
                        {
							data: 'mov',
							title: 'Mov',
							className: 'text-right',
						},
						{
							data: 'fecha',
							title: 'fecha',
							className: 'text-center',
							render: formato_fecha,
						},

						{
							data: 'nombreCliente',
							title: 'nombreCliente',
							className: 'text-left',
						},
						{
							data: 'monedaSigla',
							title: 'Moneda',
							className: 'text-right',
						},
                        {
							data: 'montoTotal',
							title: 'montoTotal',
							className: 'text-right',
							render: numberDecimal
						},
                        {
							data: 'montoTotalDolares',
							title: 'montoTotalDolares',
							className: 'text-right',
							render: numberDecimal,
                            visible: false
						},
                        {
							data: 'vendedor',
							title: 'vendedor',
							className: 'text-right',
                            visible: false
						},
                        {
							data: 'estado',
							title: 'estado',
							className: 'text-right',
                            visible: false
						},
                        {
							data: null,
							title: 'accion',
							className: 'text-center',
                            //width: '20px',
							render: function () {
								return `
									<button type="button" class="btn btn-default detalle">
										<span class="fa fa-hand-o-right" aria-hidden="true">
										</span>
									</button>
								`
							}
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
							text: '<i class="fas fa-sync" aria-hidden="true" style="font-size:18px;"></i>',
							action: function (e, dt, node, config) {
								bill.porFacturar(glob_alm_usu)
							}
						},
						{
							extend: 'collection',
							text: '<i class="fa fa-cogs" aria-hidden="true" style="font-size:18px;"></i>',
							titleAttr: 'Configuracion',
							autoClose: true,
							buttons: [
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
										pendientes.state.clear()
										bill.porFacturar(glob_alm_usu)
									}
								},
		
							]
						},
						{
							extend: 'collection',
							text: '<i class="fa fa-archive" aria-hidden="true"></i>',
							titleAttr: 'Almacenes',
							autoClose: true,
							buttons: [
								{
									extend: 'colvis',
									text: 'Central',
									action: function (e, dt, node, config) {
										bill.porFacturar('1')
									}
								},
								{
									extend: 'colvis',
									text: 'Potosí',
									action: function (e, dt, node, config) {
										bill.porFacturar('3')
									}
								},
								{
									extend: 'colvis',
									text: 'SantaCruz',
									action: function (e, dt, node, config) {
										bill.porFacturar('4')
									}
								},
		
							]
						},
					],
					order: [],
					//responsive: true,
					language: datatableLangage,
				});
				quitarcargando();
			}).fail(function (jqxhr, textStatus, error) {
				let err = textStatus + ", " + error;
				console.log("Request Failed: " + err);
			});
		},
        porFacturarDetalle(row){
			$.fn.dataTable.ext.errMode = 'none';
			$.ajax({
				type: "POST",
				url: base_url('siat/facturacion/Emitir/pendientesFacturarDetalle'),
				dataType: "json",
                data:{
                    row:row
                }
			}).done(function (res) {
				console.log(res);
				bill.egresoDetalle = res
				tableDetalle = $('#pendientesFacturarDetalle').DataTable({
					data: res,
					destroy: true,
                    scrollY: '200px',
					responsive: true,
                    scrollCollapse: true,
                    searching: false,
                    paging: false,
					//info:false,
					columnDefs:[{
						targets: "_all",
						sortable: false
					}],
					//dom: 'Bfrtip',
					/* lengthMenu: [
						[10, 25, 50, -1],
						['10 filas', '25 filas', '50 filas', 'Todo']
					], */
					pageLength: 5,
					columns: [
                       
						{
							data: 'id',
							title: 'id',
							className: 'text-center',
							visible: false
						},
                        {
							data: 'codigoProducto',
							title: 'Código',
							className: 'text-left',
						},
                        {
							data: 'descripcion',
							title: 'Descripción',
							className: 'text-left',
						},

						{
							data: 'precioUnitario',
							title: 'P/U',
							className: 'text-right',
						},
						{
							data: 'cantidad',
							title: 'Cantidad',
							className: 'text-right',
						},
                        {
							data: 'subTotal',
							title: 'Total',
							className: 'text-right',
						},
                        {
							data: null,
							title: `<button type="button" class="btn btn-default facturarTodo">
										<span class="fa fa-hand-o-down" aria-hidden="true">
										</span>
									</button>`,
							className: 'text-center',
                            //width: '20px',
							render: function () {
								return `
									<button type="button" class="btn btn-default facturarItem">
										<span class="fa fa-hand-o-down" aria-hidden="true">
										</span>
									</button>
								`
							}
						},
					],
					stateSave: true,
					stateSaveParams: function (settings, data) {
						data.order = []
					},
					buttons: [
						/* {
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
										tableDetalle.state.clear()
										bill.porFacturarDetalle()
									}
								},
		
							]
						}, */
                        {
                            text: '<i class="fa fa-plus add" aria-hidden="true" style="font-size:18px;"></i>',
                            action: function (e, dt, node, config) {
                                console.log('enviar todo');
                            }
                        },
					],
					order: [],
					//responsive: true,
					language: datatableLangage,
				});
				quitarcargando();
			}).fail(function (jqxhr, textStatus, error) {
				let err = textStatus + ", " + error;
				console.log("Request Failed: " + err);
			});
		},
		totalDetalle(){
			console.log('totalDetallessssssssss');
		},
		editRow(){
			if (this.edit === true) {
				this.validar = true
				this.validarDetalle()
				if (this.errorsDetalle.length == 0) {
					this.total()
					this.edit = false
				} 
				if (this.errors.length > 0 || this.errorsDetalle.length > 0) {
					this.validar = false
				} 
			} else {
			  this.edit = true
			  this.validar = false
			}
		},
		validarCabecera(){
			this.errors = []
			if (this.infoAlmacen.estadoCuis == 'CADUCO') {
				this.errors.push(`El CUIS esta caduco`)
			}
			if (this.infoAlmacen.estadoCufd == 'CADUCO') {
				this.errors.push(`El CUFD esta caduco`)
			}
			if (Number(this.cabecera.codigoTipoDocumentoIdentidad) < 1  || Number(this.cabecera.codigoTipoDocumentoIdentidad) > 6) {
				this.errors.push(`El código de documento de ${this.cabecera.nombreCliente} es ${this.cabecera.codigoTipoDocumentoIdentidad}
				según impuestos debe ser entre 1-5.`)
			}
			if (this.errors.length > 0) {
				console.error(this.errors);
				this.validar = false
				return false
			} else {
				console.log('sin errores Cabecera');
				this.validar = true
			}
		},
		validarTarjeta(){
			let regex = /^[0-9]{4}(-[0-9]{4})?$/
			if (regex.test(this.numeroTarjeta)) {
				let numeros = this.numeroTarjeta.split('-',2)
				this.numeroTarjeta = `${numeros[0]}00000000${numeros[1]}`
				return this.numeroTarjeta
			} else {
				return false
			}

		},
		validarDetalle(){
			this.errorsDetalle = []
			this.detalle.forEach(element => {
				if (!this.validateDecimal(Number(element.precioUnitario) )) {
					this.errorsDetalle.push(`Error decimales precio unitario en ${element.codigoProducto}`)
				}
				if (!this.validateDecimal(Number(element.cantidad))) {
					this.errorsDetalle.push(`Error decimales en cantidad en ${element.codigoProducto}`)
				}
				if (!(Number(element.cantidad) <= Number(element.cantidad_facturar))) {
					this.errorsDetalle.push(`Error cantidad mayor al egreso en ${element.codigoProducto}`)
				}
				if (Number(element.cantidad) <= 0) {
					this.errorsDetalle.push(`Error cantidad debe ser mayor a cero  en ${element.codigoProducto}`)
				}
				if (Number(element.precioUnitario) <= 0) {
					this.errorsDetalle.push(`Error precio unitario debe ser mayor a cero en ${element.codigoProducto}`)
				}
				if (element.actividadEconomica=="") {
					this.errorsDetalle.push(`Error Actividad Economica no asignada a ${element.codigoProducto}`)
				}
				if (element.codigoProductoSin == null) {
					this.errorsDetalle.push(`Error Codigo SIAT no asignado a ${element.codigoProducto}`)
				}
			})
			if (this.errorsDetalle.length > 0) {
				console.error(this.errorsDetalle);
				this.validar = false
				return false
			} else {
				console.log('sin errores');
				this.validar = true
			}
		},
		total(){
			if (this.detalle.length>0) {
				if (this.moneda == 2) {
					this.detalle.map(function (item,index,array) {
						item.subTotal = Math.round( item.cantidad * item.precioUnitario * 100) / 100
					  })
					  this.totalFactura = this.detalle.map((item, index, array) => parseFloat(item.subTotal)).reduce( (a,b)=> a+b) 
					  this.totalFactura =  Math.round( this.totalFactura * 100) / 100
					  this.montoTotalMoneda = Math.round( this.totalFactura / 6.96 * 100) / 100 
					
				} else {
					this.detalle.map(function (item,index,array) {
					  item.subTotal = Math.round( item.cantidad * item.precioUnitario * 100) / 100
					})
					this.totalFactura = this.detalle.map((item, index, array) => parseFloat(item.subTotal)).reduce( (a,b)=> a+b) 
					this.totalFactura =  Math.round( this.totalFactura * 100) / 100
					this.montoTotalMoneda = this.totalFactura
				}
			}
		},
		deleteRow(item){
			this.detalle.splice(item,1);
			this.validarDetalle()
			this.edit = false
			this.total()
			if (this.detalle.length == 0) {
				this.cabecera = []
				this.validar = false
			}
		},
		validateDecimal(valor) {
			var RE = /^\d*(\.\d{1})?\d{0,1}$/;
			if (RE.test(valor)) {
				return true;
			} else {
				return false;
			}
		},
		facturarItem(row){
			this.moneda = this.egreso.moneda_id
			this.tipoCambio = this.moneda == 2 ? this.egreso.tipoCambio : 1
			idsDetalle = this.detalle.map((el) => el.detalle_egreso_id)
			if (idsDetalle.includes( row.detalle_egreso_id)) {
				console.log(row)
				swal({
					title: 'Error',
					html: `${row.codigoProducto} ya se encuentra en facturación.`,
					type: 'error',
					showCancelButton: false,
					allowOutsideClick: false,
				})
				return false
			}

			if (this.cabecera.cliente_id === this.egreso.cliente_id || this.cabecera == false) {
				this.cabecera = this.egreso
				this.detalle.push(row)
				this.validarCabecera()
				this.validarDetalle()
				this.showErrors(this.errors, this.errorsDetalle)
				this.total()
			} else {
				swal({
					title: 'Error',
					html: `El cliente es diferente al facturado`,
					type: 'error',
					showCancelButton: false,
					allowOutsideClick: false,
				})
			}
		
		},
		facturarEgreso(){
			this.moneda = this.egreso.moneda_id
			this.tipoCambio = this.moneda == 2 ? this.egreso.tipoCambio : 1
			if (this.detalle.length == 0) {
				this.cabecera = this.egreso
				this.detalle = this.egresoDetalle
				this.validarCabecera()
				this.validarDetalle()
				this.showErrors(this.errors, this.errorsDetalle)
				this.total()
			} else {
				this.egresoDetalle.forEach(item => {
					this.facturarItem(item)
				});
			}

		},
		showErrors(errors, errorsDetalle){
			if (errors.length > 0 || errorsDetalle.length > 0) {
				errores = ''
				Object.keys(this.errors).forEach(error => {
					errores += `<li>${this.errors[error]}</li>`
				});
				Object.keys(errorsDetalle).forEach(error => {
					errores += `<li>${errorsDetalle[error]}</li>`
				});
				swal({
					title: 'Error',
					html: `Los datos enviados para la facturación con errorneos  ${errores}`,
					type: 'error',
					showCancelButton: false,
					allowOutsideClick: false,
				})
				this.validar = false
			}
		},
		customFormatter(date) {
			//return moment(date).format('D MMMM  YYYY');

			return moment(date).format('YYYY-MM-DDTHH:mm:ss.SSS');

		},
		cleanBill(){
			location.reload();
		},
		now(){
			return moment().format('YYYY-MM-DDTHH:mm:ss.SSS');
		},
        showModal(){
			this.codigoEmision = this.emision == '1' ? '1' : '2'
			if (this.metodo_pago_siat.label.includes('TARJETA')) {
				if (!this.validarTarjeta()) {
					swal({
						title: 'Error',
						html: `Error formato de Numero de Tarjeta debe ser 0000-0000`,
						type: 'error',
						showCancelButton: false,
						allowOutsideClick: false,
					})
					return
				}
			}
			if (this.codigoEmision == '2' && this.emision == '3' ) {
				if (!Number(this.numeroFacturaContingencia) > 0 || this.fechaEmision == null) {
					swal({
						title: 'Error',
						html: `Error en numero de o fecha de Factura de contingencia`,
						type: 'error',
						showCancelButton: false,
						allowOutsideClick: false,
					})
					return
				}
			}
			if (this.detalle.length == 0) {
				swal({
					title: 'Error',
					html: `Error no se tiene articulos`,
					type: 'error',
					showCancelButton: false,
					allowOutsideClick: false,
				})
				return
			}
			agregarcargando()
			let cafc = this.emision == '3' ? this.cafc : ''
			this.cabecera.user_id = glob_user_id		
			this.cabecera.glosa = this.glosa		
			let cabeceraFactura = {
				"nitEmisor": "",
				"razonSocialEmisor": "HERGO LTDA",
				"municipio": this.infoAlmacen.ciudad,
				"telefono": this.infoAlmacen.phone,
				"numeroFactura": this.emision == '3' ? this.numeroFacturaContingencia: '',
				"cuf":"",
				"cufd":this.infoAlmacen.codigoCufd,
				"codigoSucursal":this.infoAlmacen.sucursal,
				"direccion": this.infoAlmacen.address,
				"codigoPuntoVenta":this.codigoPuntoVenta,
				"fechaEmision": this.emision == '3' ? this.fechaEmision : this.now(),
				"nombreRazonSocial": this.cabecera.nombreCliente,
				"codigoTipoDocumentoIdentidad": this.cabecera.codigoTipoDocumentoIdentidad,
				"numeroDocumento": this.cabecera.numeroDocumento,
				"complemento": "",
				"codigoCliente": this.cabecera.cliente_id,
				"codigoMetodoPago": this.metodo_pago_siat.id,
				"numeroTarjeta": this.numeroTarjeta,
				"montoTotal": this.totalFactura,
				"montoTotalSujetoIva": this.totalFactura,
				"codigoMoneda": this.moneda,
				"tipoCambio": this.tipoCambio,
				"montoTotalMoneda": this.montoTotalMoneda,
				"montoGiftCard": null,
				"descuentoAdicional": "0",
				"codigoExcepcion": "",
				"cafc": cafc,
				"leyenda": this.leyenda,
				"usuario": this.usuario,
				"codigoDocumentoSector": "1"
			}
			let configuracionSiat = {
				"cuis": this.infoAlmacen.cuis,
				"tipoFacturaDocumento": "1",
				"fechaEnvio": "",
				"codigoControlCUFD":this.infoAlmacen.codigoControlCufd,
				"codigoEmision":this.codigoEmision 
			}
			let data = {
				configuracion: configuracionSiat,
				cabeceraFactura: cabeceraFactura,
				detalle: this.detalle,
				cabecera: this.cabecera
			}
			/* console.log(data);
			quitarcargando()
			return */
			$.ajax({
                type: "POST",
				url: base_url_siat('recepcionFactura'),
                dataType: "json",
                data:data
            }).done(function (res) {
				if (res.errors) {
					quitarcargando()
					errores = ''
					Object.keys(res.errors).forEach(error => {
						errores += `<li>${res.errors[error]}</li>`
					});
					swal({
						title: 'Error',
						html: `Los datos enviados para la facturación con errorneos  ${errores}`,
						type: 'error',
						showCancelButton: false,
						allowOutsideClick: false,
					})
				} else if (res.statusfactura == 'OFFLINE'){
					quitarcargando()
					swal({
						title: 'FACTURA OFFLINE',
						html: `La factura ${res.factura.cabecera.numeroFactura} se emitio de manera  
									<br> OFFLINE </br>
									No se registro en el SIAT`,
						type: 'success',
						showCancelButton: false,
						allowOutsideClick: false,
					}).then(function (result) {
							console.log(res.hasOwnProperty('idFacturaInventarios'));
							let imprimir = base_url("pdf/Siat/factura/") + res.idFacturaInventarios;
							window.open(imprimir);
							location.reload();
					});
				}
				 else if (res.statusfactura.codigoDescripcion == 'VALIDADA') {
					quitarcargando()
					respuestaEmail = res.mailEnviado ? `La factura se envió a: ${ res.mailEnviado}` : `No se tiene el correo del cliente ENVIAR MANUALMENTE`
					swal({
						title: res.statusfactura.codigoDescripcion,
						html: `La factura se autorizó por el SIAT y se guardó con exito 
									<br> ${respuestaEmail} </br>
									<br> Codigo de recepción:${res.statusfactura.codigoRecepcion}</br>`,
						type: 'success',
						showCancelButton: false,
						allowOutsideClick: false,
					}).then(function (result) {
							console.log(result);
							let imprimir = base_url("pdf/Siat/factura/") + res.idFacturaInventarios;
							window.open(imprimir);
							location.reload();
					});
				} else {
					quitarcargando()
					swal({
						title: res.statusfactura.codigoDescripcion,
						html: `${res.statusfactura.mensajesList.descripcion}</br>`,
						type: 'error',
						showCancelButton: false,
						allowOutsideClick: false,
					})
					return false
				}
            }).fail(function (jqxhr, textStatus, error) {
				quitarcargando();
				let err = textStatus + ", " + error;
				console.log("Request Failed: " + err);
				swal({
					title: 'Revisar Consulta Factura Siat',
					text: "Para imprimir vaya a consulta facturas SIAT",
					type: 'warning',
					showCancelButton: false,
					allowOutsideClick: false,
				}).then(
					function (result) {
						//agregarcargando();
						window.location.href =base_url("siat/facturacion/Emitir/consultaFacturasSiat");
				});
			});
            
        },
		cambioEmision(){
			this.validar = this.emision == 3 ? true : this.validar
		},
		setTituloFactura(){
			//this.tituloFactura = this.siatOnline ? `FACTURA ONLINE POS:${this.codigoPuntoVenta}` : 'FACTURA OFFLINE SIAT NO ESTA EN LINEA'
			//this.codigoEmision = this.siatOnline ? '1' : '2'
			//this.emision = this.siatOnline ? '1' : '2'

			if (this.siatOnline) {
				this.codigoEmision = '1'
				this.emision = 1
				this.tituloFactura = `FACTURA ONLINE PV:${this.codigoPuntoVenta}`
			} else {
				this.codigoEmision = '2'
				this.emision = 2
				this.tituloFactura = `FACTURA OFFLINE PV:${this.codigoPuntoVenta}`
				swal({
					title: 'Error',
					html: `El sistema de SIAT no esta en linea en este momento.`,
					type: 'error',
					showCancelButton: false,
					allowOutsideClick: false,
				})
			}
		},
		verificarSiat(){
			$.ajax({
                type: "GET",
				url: base_url_siat('facturacion/verificar'),
            }).done(function (res) {
				bill.siatOnline = Boolean(res)
				bill.setTituloFactura()
				return bill.siatOnline
            });
		}
	},
	filters:{
		moneda:function(value){
			num = Math.round(value * 100) / 100
			num = num.toFixed(2);
			return (formatNumber.new(num));
		},                 
	},  
})