let ini = moment().subtract(0, 'year').startOf('year').format('YYYY-MM-DD')
let fin = moment().subtract(0, 'year').endOf('year').format('YYYY-MM-DD')
$(document).ready(function () {
	$.fn.dataTable.ext.errMode = 'none';
	dataPicker()
	getData()
	$('#reportrange').on('apply.daterangepicker', function (ev, picker) {
		getData()
	});
})


function getData() {
	agregarcargando()
	$.ajax({
		type: "POST",
		url: base_url('index.php/siat/facturacion/Emitir/getFacturasSiat'),
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
			//select: true,
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
					render: tipoFactura,
					width: '5%'
				},
				{
					data: 'numeroFactura',
					title: 'Nº',
					className: 'text-center',
				},
				{
					data: 'fechaFac',
					title: 'FECHA',
					className: 'text-center',
					render: formato_fecha,
					visible: false
				},
                {
					data: 'fechaEmisionSiat',
					title: 'FECHA SIAT',
					className: 'text-center',
					render: formato_fecha_corta,
				},
                {
					data: 'ClienteNit',
					title: 'DOCUMENTO',
					width: '10%'
				},
				{
					data: 'ClienteFactura',
					title: 'CLIENTE',
				},
                {
					data: 'movEgreso',
					title: 'Movimiento',
                    render:printEgreso,
					width: '10%'
				},
				{
					data: 'total',
					title: 'TOTAL',
					sorting: false,
					className: 'text-right',
					render: numberDecimal
				},
                {
					data: 'pedido',
					title: 'PEDIDO',
					className: 'text-center',
					visible: false
				},
				{
					data: 'metodoPago',
					title: 'METODO PAGO',
					className: 'text-center',
					visible: true
				},
                {
					data: 'pagos',
					title: 'PAGOS',
                    render:printPago
				},
				{
					data: 'vendedor',
					title: 'VENDEDOR',
					className: 'text-right',
					sorting: false,
					width: '10%'
				},
                {
					data: 'fecha',
					title: 'CREADO EN:',
					className: 'text-center',
					render: formato_fecha_corta,
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
					visible: false
				},
                {
					data: 'cuf',
					title: 'CUF',
					visible: false
				},
				
                {
                    data:'pagadaF',
                    title:"Pagado",
					className: 'text-right',
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
	let anular = `<button type="button" class="btn btn-default anular">
					<span class="fa fa-ban" aria-hidden="true">
					</span>
				</button>`
	let xml = `<button type="button" class="btn btn-default xml">
					<span class="fa fa-file-text-o" aria-hidden="true">
					</span>
				</button>`
	let pdf = `<button type="button" class="btn btn-default print">
					<span class="fa fa-print" aria-hidden="true">
					</span>
				</button>`
	let verificar = `<button type="button" class="btn btn-default check">
		<span class="fa fa-check" aria-hidden="true">
		</span>
	</button>`
	let buttons = row.anulada == 1 || row.pagada == 1 ?  `${pdf}${xml}${verificar}` :  `${pdf}${xml}${anular}${verificar}`
	return buttons
}
function printEgreso(value, row, index) {
    link=''
    movEgresos = JSON.parse(value)
    movEgresos.forEach(egre => {
        let imprimir = base_url("pdf/Egresos/index/") + egre.id;
        link = link + ' '+ `<a href=${imprimir} target="_blank">${egre.n}</a>`
    });
    return link
}
function printPago(value, row, index) {
    if (value == null) {
        return '-'
    } else {
        link=''
        pagos = JSON.parse(value)
        pagos.forEach(pago => {
            let imprimir = base_url("pdf/Recibo/index/") + pago.id;
            link = link + ' '+ `<a href=${imprimir} target="_blank">${pago.n}</a>`
        });
        return link
    }

}


$(document).on("click", "button.print", function () {
    let row = getRow(table, this)
	let print = base_url(`pdf/Siat/factura/${row.idFactura}`)
	window.open(print);
})
$(document).on("click", "button.check", function () {
    let row = getRow(table, this)
	pro.verificarFactura(row)
})
$(document).on("click", "button.xml", function () {
    let row = getRow(table, this)
	let xml = `https://images.hergo.app/obs/xmls/${row.cuf}.xml`
	window.open(xml);

})
$(document).on("click", "button.anular", function () {
    let row = getRow(table, this)
	pro.showModalAnular(row)
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
		motivosAnulacion: [],
		codigoMotivo:'1',
		facturaRow:false,
		detalleAnulacion:'',
		codigoPuntoVenta:CPV,
	},
	mounted() {
		this.getMotivosAnulacion()
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
		showModalAnular(row){
			console.log(row);
			this.facturaRow = row
			$("#anular").modal("show");
		},
		siatAnular(){
			agregarcargando()
			dataSiat = {
				"cliente": {
					"codigoPuntoVenta": this.facturaRow.codigoPuntoVenta,
					"codigoSucursal": this.facturaRow.codigoSucursal,
					"codigoDocumentoSector": "1",
					"codigoEmision": "1",
					"cufd": this.infoAlmacen.codigoCufd,
					"cuis": this.infoAlmacen.cuis,
					"tipoFacturaDocumento": "1",
					"codigoMotivo": this.codigoMotivo,
					"cuf": this.facturaRow.cuf,
					"nombreRazonSocial": this.facturaRow.ClienteFactura,
					"numeroFactura": this.facturaRow.numeroFactura,
					"emailCliente": this.facturaRow.emailCliente,
				}
			}
			data = {
				detalleAnulacion: this.detalleAnulacion,
				factura_id: this.facturaRow.idFactura,
				almacen_id: this.facturaRow.idAlmacen,
				user_id: glob_user_id
			}
			$.ajax({
				type: "POST",
				url: base_url_siat('anulacionFactura'),
				dataType: "json",
				data:{
					dataSiat : dataSiat,
					data: data
					
                }
			}).done(function (res) {
				quitarcargando()
				console.log(this.facturaRow);
				let respuestaSiat = res.siat.RespuestaServicioFacturacion
				let transaccion = respuestaSiat.transaccion
				let titulo = respuestaSiat.codigoDescripcion
				let descripcion = transaccion ?  `La factura se anuló con exito en el SIAT y en el sistema de inventarios. <br> Se envio notificación a: ${res.mailEnviado}` : respuestaSiat.mensajesList.descripcion
				swal({
					title: titulo,
					html: `${descripcion} `,
					type: transaccion ? 'success' : 'error',
					showCancelButton: false,
					allowOutsideClick: false,
				}).then(function (result) {
						if (transaccion) {
							location.reload();
						}
				});
				quitarcargando();
			}).fail(function (jqxhr, textStatus, error) {
				let err = textStatus + ", " + error;
				console.log("Request Failed: " + err);
			});
		},
		getMotivosAnulacion(){
			$.ajax({
				type: "POST",
				url: base_url('siat/facturacion/Emitir/getMotivosAnulacion'),
				dataType: "json",
			}).done(function (res) {
				pro.motivosAnulacion = res
			})
		},
		verificarFactura(row){
			data = {
				codigoPuntoVenta: row.codigoPuntoVenta,
				codigoSucursal: row.codigoSucursal,
				codigoDocumentoSector: "1",
				codigoEmision: "1",
				cufd: this.infoAlmacen.codigoCufd,
				cuis: row.cuis,
				tipoFacturaDocumento: "1",
				cuf: row.cuf
			}
			$.ajax({
				type: "POST",
				url: base_url_siat('verificarFactura'),
				dataType: "json",
				data:{
					data: data
                }
			}).done(function (res) {
				quitarcargando()
				if (res.transaccion) {
					swal({
						title: res.codigoDescripcion,
						html: `${res.codigoRecepcion} </br>
								${res.codigoEstado}	`,
						type: res.codigoEstado == '691' ? 'warning' : 'success',
						showCancelButton: false,
						allowOutsideClick: false,
					})
				} else {
					console.log(res);
					swal({
						title: res.codigoDescripcion,
						html: `Código estado: ${res.codigoEstado}	
								<br>${res.mensajesList.descripcion}
								`,
						type: 'error',
						showCancelButton: false,
						allowOutsideClick: false,
					})
				}
				
				quitarcargando();
			}).fail(function (jqxhr, textStatus, error) {
				let err = textStatus + ", " + error;
				console.log("Request Failed: " + err);
			});
		}

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