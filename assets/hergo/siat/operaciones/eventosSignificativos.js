Vue.component('vue-ctk-date-time-picker', window['vue-ctk-date-time-picker']);
const app = new Vue({
	el: '#app',
    components: {
        vuejsDatepicker
    },
	data: {
        almacen:document.getElementById("idAlmacenUsuario").value,
        eventos: false,
        codigoPuntoVenta: CPV,
        dataSiat:false,
        fecha: '',
        es: vdp_translation_es.js,
        motivos: false,
        motivo:'1',
        descripcion:'',
        fechaRegistro:{
            date: new Date()
        },
        /* registroFecha: {
            date: '' //new Date()
        }, */
        fechaHoraFinEvento:'',//moment().format('YYYY-MM-DDTHH:mm:ss.SSS'),
        fechaHoraInicioEvento:'', //moment().format('YYYY-MM-DDTHH:mm:ss.SSS')
        state :{
            date: ''
        },
        registroFecha: '',
        cufdEvento:'',
        codigoEvento: '',
        codigoRecepcion:''

	},
	mounted() {
        this.verificar()
        this.get_codigos()
	},
	methods:{
		
        get_codigos(){
			$.ajax({
				type: "POST",
				url: base_url('siat/facturacion/Emitir/getCodigos'),
				dataType: "json",
				data:{
					codigoPuntoVenta : this.codigoPuntoVenta
                }
			}).done(function (res) {
				app.dataSiat = res
			})
		},
        get_eventos(){
            agregarcargando()
            if (this.fecha == '') {
                swal({
                    title: 'Error',
                    html: `Ingrese fecha valida`,
                    type: 'error',
                    showCancelButton: false,
                    allowOutsideClick: false,
                })
                quitarcargando()
                return false
            }
			$.ajax({
				type: "POST",
				url: base_url_siat('operaciones/consultaEventoSignificativo'),
				dataType: "json",
				data:{
                        "cuis": this.dataSiat.cuis,
                        "codigoSucursal": this.dataSiat.sucursal,
                        "codigoPuntoVenta":this.dataSiat.codigoPuntoVenta,
                        "fechaEvento": moment(this.fecha).format('YYYY-MM-DD') ,
                        "cufd": this.dataSiat.codigoCufd
                }
			}).done(function (res) {
                if (res.RespuestaListaEventos.transaccion) {
                    eventos = res.RespuestaListaEventos.listaCodigos
                    if (eventos.hasOwnProperty('codigoEvento')) {
                        app.eventos = [res.RespuestaListaEventos.listaCodigos]
                    } else {
                        app.eventos = res.RespuestaListaEventos.listaCodigos
                    }
                } else {
                    quitarcargando()
                    swal({
                        title: 'Error',
                        html: `${res.RespuestaListaEventos.mensajesList.descripcion}`,
                        type: 'error',
                        showCancelButton: false,
                        allowOutsideClick: false,
                    })
                    //document.getElementById("table").remove();
                    return false
                }
                table = $('#table').DataTable({
                    data: app.eventos,
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
                            data: 'codigoEvento',
                            title: 'codigoEvento',
                            className: 'text-center',
                            //visible: false
                        },
                        {
                            data: 'codigoRecepcionEventoSignificativo',
                            title: 'codigoRecepcionEventoSignificativo',
                            className: 'text-center',
                        },
                        {
                            data: 'fechaInicio',
                            title: 'fechaInicio',
                            className: 'text-center',
                        },
                        {
                            data: 'fechaFin',
                            title: 'fechaFin',
                            className: 'text-center',
                        },
                        {
                            data: 'descripcion',
                            title: 'descripcion',
                        },
                    ],
                    stateSave: true,
                    stateSaveParams: function (settings, data) {
                        data.order = []
                    },
                    order: [],
                    fixedHeader: {
                        header: true,
                        footer: true
                    },
       
                });
				quitarcargando();
			}).fail(function (jqxhr, textStatus, error) {
				let err = textStatus + ", " + error;
				console.log("Request Failed: " + err);
			});
		},
        verificar(){
            $.ajax({
				type: "GET",
				url: base_url_siat('operaciones/verificar'),
				dataType: "json",
			}).done(function (res) {
                if (!res.transaccion) {
                    swal({
                        title: 'Error Conexión',
                        text: "Error de conexión con el SIAT vuelva intentar",
                        type: 'error',
                        showCancelButton: false,
                        allowOutsideClick: false,
                    })
                }

			}).fail(function (jqxhr, textStatus, error) {
				let err = textStatus + ", " + error;
				console.log("Request Failed: " + err);
                swal({
                    title: 'Error Conexión',
                    text: "Error de conexión con el SIAT vuelva intentar",
                    type: 'error',
                    showCancelButton: false,
                    allowOutsideClick: false,
                })
			});
        },
        customFormatter(date) {
            return moment(date).format('DD MMMM YYYY');
        },
        showModal(){
            $("#modal").modal("show");
            this.getMotivosAnulacion()
        },
        getMotivosAnulacion(){
			$.ajax({
				type: "POST",
				url: base_url('siat/facturacion/Emitir/getMotivosEvento'),
				dataType: "json",
			}).done(function (res) {
				app.motivos = res
			})
		},
        formatFechaSiat(date){
            return moment(date).format('YYYY-MM-DDTHH:mm:ss.SSS');
        },
        registrarEvento(){
            agregarcargando()
            if (this.fechaHoraInicioEvento == '' || this.fechaHoraFinEvento == '' || this.cufdEvento== '') {
                quitarcargando()
                swal({
					title: 'Error',
					text: "Llenar correctamente el formulario",
					type: 'error',
					showCancelButton: false,
					allowOutsideClick: false,
				})
                return
            }

            data = {
                    "cuis": this.dataSiat.cuis,
                    "codigoSucursal": this.dataSiat.sucursal,
                    "codigoPuntoVenta": this.dataSiat.codigoPuntoVenta,
                    "codigoMotivoEvento": this.motivo,
                    "cufdEvento": this.cufdEvento,
                    "descripcion": this.descripcion,
                    "fechaHoraInicioEvento": this.formatFechaSiat(this.fechaHoraInicioEvento),
                    "fechaHoraFinEvento": this.formatFechaSiat(this.fechaHoraFinEvento),
                    "cufd": this.dataSiat.codigoCufd
            }
            $.ajax({
				type: "POST",
				url: base_url_siat('operaciones/registroEventoSignificativo'),
				dataType: "json",
				data:data
			}).done(function (res) {
                quitarcargando()
                res = res.RespuestaListaEventos
                if (res.transaccion) {
                    swal({
						title: 'Evento Registrado',
						html: `El codigo del evento es: ${res.codigoRecepcionEventoSignificativo}`,
						type: 'success',
						showCancelButton: false,
						allowOutsideClick: false,
					}).then(function (result) {
							console.log(result);
							location.reload();
					});
                } else {
                    swal({
						title: 'Error',
						html: `${res.mensajesList.descripcion}`,
						type: 'error',
						showCancelButton: false,
						allowOutsideClick: false,
					})
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
            //console.log(data);
        },
        enviarPaquete(){
            agregarcargando()
            data = {
                "codigoPuntoVenta": this.dataSiat.codigoPuntoVenta,
                "codigoSucursal": this.dataSiat.sucursal,
                "codigoDocumentoSector": '1',
                "codigoEmision": '2',
                "cufd": this.dataSiat.codigoCufd,
                "cuis": this.dataSiat.cuis,
                "tipoFacturaDocumento": '1',
                "fechaEnvio": moment().format('YYYY-MM-DDTHH:mm:ss.SSS'),
                "cafc": '',//'1018E82642F3D',
                "codigoEvento": this.codigoEvento,
                "cantidadFacturas": '5'
            }
            $.ajax({
				type: "POST",
				url: base_url_siat('recepcionPaqueteLote'),
				dataType: "json",
				data:data
                }).done(function (res) {
                console.log(res.codigoRecepcion);
                app.codigoRecepcion = res.codigoRecepcion
                quitarcargando()

			})
            //console.log(data);
        },
        validacionRecepcionPaquete(){
            agregarcargando()
            data = {
                "codigoPuntoVenta": this.dataSiat.codigoPuntoVenta,
                "codigoSucursal": this.dataSiat.sucursal,
                "codigoDocumentoSector": '1',
                "codigoEmision": '2',
                "cufd": this.dataSiat.codigoCufd,
                "cuis": this.dataSiat.cuis,
                "tipoFacturaDocumento": '1',
                "fechaEnvio": moment().format('YYYY-MM-DDTHH:mm:ss.SSS'),
                "cafc": '',
                "codigoRecepcion": this.codigoRecepcion,
                "cantidadFacturas": '500'
            }
            $.ajax({
				type: "POST",
				url: base_url_siat('validacionRecepcionPaqueteFacturaLote'),
				dataType: "json",
				data:data
                }).done(function (res) {
                console.log(res.RespuestaServicioFacturacion.codigoDescripcion);

                quitarcargando()

			})
            //console.log(data);
        },
        doSomethingInParentComponentFunction(){
            console.log('object');
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