let ini = moment().subtract(0, 'year').startOf('year').format('YYYY-MM-DD')
let fin = moment().subtract(0, 'year').endOf('year').format('YYYY-MM-DD')
let permisoAnular
let fechaLimiteAnulacion = null;
let fechaLimiteAnulacionGlobal = null;

$(document).ready(function () {
	permisoAnular = $("#permisoAnular").val()
    console.log(permisoAnular);
	$.fn.dataTable.ext.errMode = 'none';
	dataPicker()
	getData()
	$('#reportrange').on('apply.daterangepicker', function (ev, picker) {
		getData()
	});
    getFechaLimiteAnulacion().done(function(res) {
        fechaLimiteAnulacion = res.fecha_limite_anulacion;
    });
})

function getPeriodoAbierto(fechaLimite) {
    // El periodo abierto es el mes anterior a la fecha límite
    let limite = moment(fechaLimite);
    return {
        year: limite.subtract(1, 'month').year(),
        month: limite.month() // 0-indexed
    };
}

function isFacturaMesActual(fechaFac) {
    let fecha = moment(fechaFac);
    let ahora = moment();
    return fecha.year() === ahora.year() && fecha.month() === ahora.month();
}

function isFacturaMesAnterior(fechaFac, fechaLimite) {
    let fecha = moment(fechaFac);
    let periodo = getPeriodoAbierto(fechaLimite);
    return fecha.year() === periodo.year && fecha.month() === periodo.month;
}

function isFacturaMesAnteriorOMasVieja(fechaFac, fechaLimite) {
    let fecha = moment(fechaFac);
    let periodo = getPeriodoAbierto(fechaLimite);
    if (fecha.year() < periodo.year) return true;
    if (fecha.year() === periodo.year && fecha.month() < periodo.month) return true;
    return false;
}

function getData() {
    agregarcargando();
    getFechaLimiteAnulacion().done(function(res) {
        fechaLimiteAnulacionGlobal = res.fecha_limite_anulacion;
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
                        data: 'idAlmacen',
                        title: 'ALM',
                        className: 'text-center',
                        visible: false

                    },
                    {
                        data: 'almacen',
                        title: 'ALMACEN',
                        className: 'text-center'
                        
                    },
                    {
                        data: 'numeroFactura',
                        title: 'Nº',
                        className: 'text-center',
                    },
                    {
                        data: 'numeroFacturaSearch',
                        visible: false
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
                        visible: false,
                        //searchable: false
                    },
                    {
                        data: 'leyenda',
                        title: 'LEYENDA',
                        visible: false
                    },
                    {
                        data: 'cuf',
                        title: 'CUF',
                        visible: false,
                        //searchable: false
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
                            //pro.verificarEvento()
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
    let linkSiat = `<button type="button" class="btn btn-default linkSiat" title="top">
        <span class="fa fa-external-link" aria-hidden="true">
        </span>
    </button>`
    let buttons = `${pdf}${xml}${linkSiat}`;
    if (row.anulada == 1 || row.pagada == 1) {
        return buttons;
    }
    if (!fechaLimiteAnulacionGlobal) {
        return buttons;
    }
    let ahora = moment();
    let limite = moment(fechaLimiteAnulacionGlobal);
    // Mostrar botón si es del mes actual
    if (isFacturaMesActual(row.fechaFac)) {
        if (permisoAnular == 'true') {
            return `${pdf}${xml}${anular}${linkSiat}`;
        } else {
            return buttons;
        }
    }
    // Mostrar botón si es del periodo abierto (mes anterior a la fecha límite) y estamos antes de la fecha límite
    if (isFacturaMesAnterior(row.fechaFac, fechaLimiteAnulacionGlobal) && ahora.isBefore(limite)) {
        if (permisoAnular == 'true') {
            return `${pdf}${xml}${anular}${linkSiat}`;
        } else {
            return buttons;
        }
    }
    // Nunca mostrar botón para meses anteriores al periodo abierto
    return buttons;
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
	let print = base_url(`pdf/Siat/factura/${row.idFactura}?con_titulo=0`)
	window.open(print);
})
$(document).on("click", "button.linkSiat", function () {
    let row = getRow(table, this)
	//let link = `https://pilotosiat.impuestos.gob.bo/consulta/QR?nit=1000991026&cuf=${row.cuf}&numero=${row.numeroFactura}&t=2`
	let link = `https://siat.impuestos.gob.bo/consulta/QR?nit=1000991026&cuf=${row.cuf}&numero=${row.numeroFactura}&t=2`
	window.open(link);
})
$(document).on("click", "button.check", function () {
    let row = getRow(table, this)
	console.log(row.codigoRecepcion);
	if (row.codigoRecepcion == '') {
		pro.validarFacturasCufs()
	} else {
		pro.verificarFactura(row)
	}

})
$(document).on("click", "button.xml", function () {
    let row = getRow(table, this)
	let xml = `https://images.hergo.app/obs/xmls/${row.cuf}.xml`
	window.open(xml);

})
$(document).on("click", "button.anular", function () {
    // Consultar la fecha límite en tiempo real antes de mostrar el modal
    getFechaLimiteAnulacion().done(function(res) {
        let fechaLimiteAnulacion = res.fecha_limite_anulacion;
        if (!fechaLimiteAnulacion) {
            swal({
                title: 'Error',
                html: 'No se pudo obtener la fecha límite de anulación. Intente de nuevo o contacte a soporte.',
                type: 'error',
                showCancelButton: false,
                allowOutsideClick: false,
            });
            return;
        }
        let ahora = moment();
        let limite = moment(fechaLimiteAnulacion);
        if (ahora.isAfter(limite)) {
            swal({
                title: 'No permitido',
                html: 'La anulación de facturas para este periodo ya no está permitida.',
                type: 'error',
                showCancelButton: false,
                allowOutsideClick: false,
            });
            return;
        }
        let row = getRow(table, this)
        pro.showModalAnular(row)
    }.bind(this)).fail(function() {
        swal({
            title: 'Error',
            html: 'No se pudo consultar la fecha límite de anulación. Intente de nuevo o contacte a soporte.',
            type: 'error',
            showCancelButton: false,
            allowOutsideClick: false,
        });
    });
})

const pro = new Vue({
	el: '#app',
	data: {
        almacen:document.getElementById("idAlmacenUsuario").value,
        almacenes: [
            { alm: 'CENTRAL HERGO - 0', value: '1' },
            { alm: 'POTOSI - 6', value: '3' },
            { alm: 'SANTA CRUZ - 5', value: '4' },
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
		//this.validarFacturasCufs()
		this.getMotivosAnulacion()
		this.get_codigos()
	},
	methods:{
		validarFacturasCufs(){
			agregarcargando()
			$.ajax({
				type: "GET",
				url: base_url_siat('validarFacturasCufs'),
				dataType: "json",
			}).done(function (res) {
				if (res.status == 'sin facturas' || res.status =='sin conexion siat') {
					console.log(res.status);
				} else {
					console.log(res);
					pro.get_codigos()
					getData()
				}
			}).fail(function (jqxhr, textStatus, error) {
				let err = textStatus + ", " + error;
				console.log("Request Failed: " + err);
			});
		},
		verificarEvento(){
			agregarcargando()
			$.ajax({
				type: "GET",
				url: base_url_siat('verificarEvento'),
				dataType: "json",
			}).done(function (res) {
				if (res.status == 'sin facturas' || res.status =='sin conexion siat') {
					console.log(res.status);
				} else {
					console.log(res);
					pro.get_codigos()
					getData()
				}
			}).fail(function (jqxhr, textStatus, error) {
				let err = textStatus + ", " + error;
				console.log("Request Failed: " + err);
			});
		},
		enviarPaquete(){
			agregarcargando()
			$.ajax({
				type: "GET",
				url: base_url_siat('enviarPaquete'),
				dataType: "json",
			}).done(function (res) {
				if (res.status == 'sin facturas' || res.status =='sin conexion siat') {
					console.log(res.status);
				} else {
					console.log(res);
					pro.get_codigos()
					getData()
				}
			}).fail(function (jqxhr, textStatus, error) {
				let err = textStatus + ", " + error;
				console.log("Request Failed: " + err);
			});
		},
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
			//console.log(row);
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
				console.log(res);
				if (Object.hasOwn(res.siat,'errors')) {
					swal({
						title: 'ERROR SIAT',
						html: `El SIAT esta fuera de linea`,
						type: 'error',
						showCancelButton: false,
						allowOutsideClick: false,
					})
					return
				}
				let respuestaSiat = res.siat.RespuestaServicioFacturacion
				let transaccion = respuestaSiat.transaccion
				let titulo = respuestaSiat.codigoDescripcion
				let email = res.mailEnviado
				let msj = email ? `La factura se anuló con exito en el SIAT y en el sistema de inventarios. <br> Se envio notificación a: ${res.mailEnviado}`: `La factura se anuló con exito en el SIAT y en el sistema de inventarios. <br> <b>Notificar al cliente</b>.`
				let descripcion = transaccion ?  msj : respuestaSiat.mensajesList.descripcion
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
				data:data
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
				} else if(Object.hasOwn(res,'errors')){
					swal({
						title: 'ERROR SIAT',
						html: `El SIAT esta fuera de linea`,
						type: 'error',
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

function getFechaLimiteAnulacion() {
    return $.ajax({
        type: "GET",
        url: base_url('index.php/siat/facturacion/Emitir/getFechaLimiteAnulacion'),
        dataType: "json"
    });
}