let ini = moment().subtract(0, 'year').startOf('year').format('YYYY-MM-DD')
let fin = moment().subtract(0, 'year').endOf('year').format('YYYY-MM-DD')
$(document).ready(function () {
	$.fn.dataTable.ext.errMode = 'none';
	dataPicker()
	getFacturaProveedores()
	$('#reportrange').on('apply.daterangepicker', function (ev, picker) {
		getFacturaProveedores()
	});

})
$(document).on("change", "#estadoFiltro", function () {
    getFacturaProveedores()
})

function getFacturaProveedores() {
	agregarcargando()
	let filtro =  $("#estadoFiltro").val()
    //console.log(ini + ' -  ' + fin);
	$.ajax({
		type: "POST",
		url: base_url('index.php/Importaciones/FacturaProveedores/getFacturaProveedores'),
		dataType: "json",
		data: {
						ini:ini,
						fin:fin,
						filtro: filtro
					},
	}).done(function (res) {
        console.log(res);
		table = $('#tableFP').DataTable({
			data: res,
			destroy: true,
			dom: 'Bfrtip',
			lengthMenu: [
				[10, 25, 50, -1],
				['10 filas', '25 filas', '50 filas', 'Todo']
			],
			pageLength: 10,
			columns: [
				{
					data: 'orden',
					title: 'PEDIDO',
					className: 'text-center',
                },
                {
					data: filtro == 'pedidos' ? 'proveedor' : 'glosa',
					title: filtro == 'pedidos' ? 'PROVEEDOR' : 'PEDIDOS',
					
					//sorting: nameCliente == '' || nameCliente == 'TODOS' ? true : false
				},
				{
					data: filtro == 'pedidos' ? '' : 'transporte',
					title: filtro == 'pedidos' ? '' : 'TRASNPORTE',
					visible:filtro == 'pedidos' ? false : true,
                },
                {
					data: 'fecha',
					title: 'FECHA EMISIÓN',
					className: 'text-center',
					render: formato_fecha,
                },
				{
					data: 'facN',
					title: 'Nº FACTURA',
					className: 'text-center',
				},
                {
					data: 'tiempo_credito',
					title: 'CRÉDITO',
					//sorting: nameCliente == '' || nameCliente == 'TODOS' ? true : false
				},
				{
					data: 'vencimiento',
					title: 'VENCIMIENTO',
					className: 'text-center',
					render: formato_fecha,
                },
				{
					data: 'monto',
					title: 'MONTO',
					className: 'text-right',
					sorting: false,
					render: numberDecimal
				},
				{
					data: 'saldo',
					title: 'SALDO',
					className: 'text-right',
					sorting: false,
					render: numberDecimal
				},
				{
					data: 'estado',
					title: 'ESTADO',
				},
				{
					data: null,
					title: '',
					width: '100px',
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
						getFacturaProveedores()
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
								getFacturaProveedores()
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
if (row.url) {
	return `
    <button type="button" class="btn btn-default see">
		<span class="fa fa-search" aria-hidden="true">
		</span>
	</button>
	<button type="button" class="btn btn-default addPago">
		<span class="fa fa-plus" aria-hidden="true">
		</span>
	</button>
	`
} else {
	return `
	<button type="button" class="btn btn-default addPago">
		<span class="fa fa-plus" aria-hidden="true">
		</span>
	</button>
`
}

}
function aprobados (data, type, row) {
	//console.log(row.nAprobados>);
	if (row.nAprobados>2) {
		//console.log(`id ${row.id} npedidos ${row.n}`);
		return `<span class="label label-success">APROBADO</span>`
	} else {
		return `<span class="label label-danger">PENDIENTE</span>`
	}
}

$(document).on("click", "button.see", function () {
	let row = table.row( $(this).parents('tr') ).data();
    console.log(row.url == true);
    let pdf = base_url("assets/facComProv/") + row.url;
    window.open(pdf)
})

$(document).on("click", "button.addPago", function () {
	let row = table.row( $(this).parents('tr') ).data();
	console.log(row);
	pago.addPago(row)
})

Vue.component('modal', {
	template: '#modal-template',
	components: {
        vuejsDatepicker
	},
	data: function(){
        return{
			es: vdp_translation_es.js, 
			fecha:'',
			n:'',
			credito:0,
			monto:0,
			transporte:'',
			glosa:'',
			showModal: false,
			errors:''             
        }
	},
	methods:{     
		customFormatter(date) {
			return moment(date).format('D MMMM  YYYY');
		},
		close() {
			 pago.showModal = false
		},
		saveFactServ(){
			agregarcargando()
			if (this.monto<=0 || !this.fecha || !this.transporte|| !this.n) {
				quitarcargando()
				this.errors = 'Revise el Formulario'
				return
			}else{
				this.errors = ''
			} 
			let formData = new FormData($('#formFactServ')[0]); 
			formData.append('fecha', moment(this.fecha).format('YYYY-MM-DD'))
			formData.append('id_orden', '0')
			formData.append('id_proveedor', '55')

			for(let pair of formData.entries()) {
				console.log(pair[0]+ ', '+ pair[1]); 
			} 
			this.close()
			/* quitarcargando()
			return */
			$.ajax({
				url: base_url("index.php/Importaciones/OrdenesCompra/storeAsociarFactura"),
				type: 'POST',
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				success: function (returndata) {
					res = JSON.parse(returndata)
					console.log(res);
					if (res != false) {
						quitarcargando()
						swal({
							title: "Registrado!",
							text: "Factura de Servicios agregada!",
							type: "success",        
							allowOutsideClick: false,                                                                        
							}).then(function(){
								getFacturaProveedores()
								console.log('toto ok');
							})
					} else {
						quitarcargando()
						swal(
							'Error',
							'Error al guardar el Pago',
							'error'
						)
					}
				},
				error : function (returndata) {
					swal(
						'Error',
						'Error',
						'error'
					)
				},
			});
		
		},
    },
})
Vue.component('app-row',{
    template:'#row-template',
    props:['pagar','index'],

    data: function(){
        return{
            montopagar:0, 
			editing:false,  
			error:''          
        }
    },
    created:function(){   
        this.montopagar=parseFloat(this.pagar.saldo).toFixed(2);
    
    },
    methods:{     
        remove:function(){
            this.$emit('removerfila',this.index);
        },
        update:function(){
			if(parseFloat(this.montopagar) > parseFloat(this.pagar.saldo))
            {
                this.error="El monto a pagar es mayor al saldo";
				return
            }
            this.pagar.monto=parseFloat(this.montopagar).toFixed(2);
			this.editing=false;
			pago.save = true
        },
        edit:function(){    
			this.editing=true;
			pago.save = false
			this.montopagar=parseFloat(this.pagar.saldo).toFixed(2);
			pago.getTotalPago()
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
    directives: {
        inputmask: {
          bind: function(el, binding, vnode) {
            $(el).inputmask({
                alias:"decimal",
                digits:2,
                groupSeparator: ',',
                autoGroup: true,
                autoUnmask:true
            }, {
              isComplete: function (buffer, opts) {
                vnode.context.value = buffer.join('');
              }
            });
          },
        }
      },
    
});

const pago = new Vue({
	el: '#app',
	components: {
        vuejsDatepicker
    },
	data: {
		fechaPago: '',
		id_proveedor:'',
        proveedor:'',
		url:'',
		pagoslist:[],
		totalPago:0,
		es: vdp_translation_es.js,
		showModal: false,
		save:true
	},
	methods:{
		facturaServicios(){
			$("#facturaServicios").modal("show");
		},
		addPago(row){
			if(this.pagoslist.map((el) => el.id_fact_prov).indexOf(row.id_fact_prov)>=0)
			{
				swal("Atencion", "Esta factura ya fue agregada","info")
				return
			}
			
			pago.pagoslist.push(row)
		},
		deleteRow:function(index){        
            this.pagoslist.splice(index,1);
        },
		savePago(e){
			agregarcargando()
			e.preventDefault()
			if (parseFloat(this.totalPago) <=0 || !this.fechaPago || !this.save) {
				quitarcargando()
				swal(
					'Error',
					'Revise el formulario',
					'error'
				)
				return
			}
			let formData = new FormData($('#formPago')[0]); 
			formData.append('fechaPago', moment(pago.fechaPago).format('YYYY-MM-DD'))
			formData.append('total', pago.totalPago)
			formData.append('pagos', JSON.stringify(this.pagoslist))


			/* for(let pair of formData.entries()) {
				console.log(pair[0]+ ', '+ pair[1]); 
			}  */
			$.ajax({
				url: base_url("index.php/Importaciones/FacturaProveedores/storePago"),
				type: 'POST',
				data: formData,
				cache: false,
				contentType: false,
				processData: false,
				success: function (returndata) {
					res = JSON.parse(returndata)
					console.log(res);
					if (res.status == true) {
						quitarcargando()
						swal({
							title: "Registrado!",
							text: "El pago se asoció con éxito",
							type: "success",        
							allowOutsideClick: false,                                                                        
							}).then(function(){
								agregarcargando()
								location.reload();
							})
					} else {
						quitarcargando()
						swal(
							'Error',
							'Error al guardar el Pago',
							'error'
						)
					}
				},
				error : function (returndata) {
					swal(
						'Error',
						'Error',
						'error'
					)
				},
			});
		},
		cancel(e){
			e.preventDefault()
			console.log('cancelar'); return 
			window.location.href=base_url("index.php/Importaciones/Pedidos");
		},
		customFormatter(date) {
			return moment(date).format('D MMMM  YYYY');
		},
		getTotalPago:function(){
            let total=0
            $.each(this.pagoslist,function(index,value){
                total+=parseFloat(value.monto);
            })
			this.totalPago=total.toFixed(2)
			console.log(this.totalPago);
			return this.totalPago;
			
        },

	},
	filters:{
		moneda:function(value){
			num=Math.round(value * 100) / 100
			num=num.toFixed(2);
			//return(num);
			return numeral(num).format('0,0.00');            
		}, 
	}
})