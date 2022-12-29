Vue.component("v-select", VueSelect.VueSelect);
const app = new Vue({
	el: '#app',
    components: {
        vuejsDatepicker
    },
	data: {
        cuis:'',
        codigoSucursal:'',
        puntos: false,
        codigoPuntoVenta: '',
        tiposPuntoVenta: [],
        codigoTipoPuntoVenta: [],
        descripcion:'',
        nombrePuntoVenta:'',

	},
	mounted() {
        this.verificar()
        this.getTipoPuntoVenta()
	},
	methods:{
        getTipoPuntoVenta(){
            $.ajax({
                type: "POST",
                url: base_url('index.php/siat/sincronizacion/Sincronizar/tipoPuntoVenta'),
                dataType: "json",
            }).done(function (res) {
                app.tiposPuntoVenta = res
            });
        },
        get_putnosVenta(){
            agregarcargando()
            if (this.cuis == '' || this.codigoSucursal == '') {
                swal({
                    title: 'Error',
                    html: `Ingrese CUIS y Punto de Venta validas`,
                    type: 'error',
                    showCancelButton: false,
                    allowOutsideClick: false,
                })
                quitarcargando()
                return false
            }
			$.ajax({
				type: "POST",
				url: base_url_siat('operaciones/consultaPuntoVenta'),
				dataType: "json",
				data:{
					"cliente": {
                        "cuis": this.cuis,
                        "codigoSucursal": this.codigoSucursal,
                    }
                }
			}).done(function (res) {
                console.log(res);
                if (res.RespuestaConsultaPuntoVenta.transaccion) {
                    puntos = res.RespuestaConsultaPuntoVenta.listaPuntosVentas
                    if (puntos.hasOwnProperty('codigoPuntoVenta')) {
                        app.puntos = [res.RespuestaConsultaPuntoVenta.listaPuntosVentas]
                    } else {
                        app.puntos = res.RespuestaConsultaPuntoVenta.listaPuntosVentas
                    }
                } else {
                    quitarcargando()
                    swal({
                        title: 'Error',
                        html: `${res.RespuestaConsultaPuntoVenta.mensajesList.descripcion}`,
                        type: 'error',
                        showCancelButton: false,
                        allowOutsideClick: false,
                    })
                    //document.getElementById("table").remove();
                    return false
                }
                table = $('#table').DataTable({
                    data: app.puntos,
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
                            data: 'codigoPuntoVenta',
                            title: 'codigoPuntoVenta',
                            className: 'text-center',
                            //visible: false
                        },
                        {
                            data: 'nombrePuntoVenta',
                            title: 'nombrePuntoVenta',
                            className: 'text-center',
                        },
                        {
                            data: 'tipoPuntoVenta',
                            title: 'tipoPuntoVenta',
                            className: 'text-center',
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

        showModal(){
            $("#modal").modal("show");
        },
        showModalCierre(){
            $("#modalCierre").modal("show");
        },
        registrarPuntoventa(){
            if (this.codigoTipoPuntoVenta.length == 0 || this.descripcion == '' || this.nombrePuntoVenta == '' || this.cuis == '' || this.codigoSucursal == '' || this.nombrePuntoVenta == '') {
                swal({
                    title: 'Error ',
                    text: 'Llenar correctamente el formulario',
                    type: 'error', 
                    showCancelButton: false,
                })
                return
            }
            agregarcargando()
            $.ajax({
                type: "POST",
                url: base_url_siat('operaciones/puntoVenta'),
                dataType: "json",
                data: {
                    "cliente": {
                        "cuis":this.cuis,
                        "codigoSucursal": this.codigoSucursal,
                        "codigoTipoPuntoVenta": app.codigoTipoPuntoVenta.codigoClasificador,
                        "descripcion": this.descripcion,
                        "nombrePuntoVenta": this.nombrePuntoVenta,
                        "almacen_id": glob_alm_usu
                    }
                },
            }).done(function (res) {
                quitarcargando()
                if (res.client > 0) {
                    quitarcargando()
                    swal({
                        title: 'PUNTO CREADO',
                        html: `El punto de venta: ${res.puntoVenta.RespuestaRegistroPuntoVenta.codigoPuntoVenta} fue creado.`,
                        type: 'success',
                        showCancelButton: false,
                        allowOutsideClick: false,
                    })
                    $("#modal").modal("hide");
                    app.get_putnosVenta()

                } else {
                    quitarcargando()
                    swal({
                        title: 'ERROR',
                        html: `${res.RespuestaRegistroPuntoVenta.mensajesList.descripcion}`,
                        type: 'error',
                        showCancelButton: false,
                        allowOutsideClick: false,
                    })
                }
               console.log(res.RespuestaRegistroPuntoVenta);

            }).fail(function (jqxhr, textStatus, error) {
                let err = textStatus + ", " + error;
                console.log("Request Failed: " + err);
            });

        },
        cierrePuntoVenta(){
            if (this.cuis == '' || this.codigoSucursal == '' || this.codigoPuntoVenta == '') {
                swal({
                    title: 'Error ',
                    text: 'Llenar correctamente el formulario',
                    type: 'error', 
                    showCancelButton: false,
                })
                return
            }
            agregarcargando()
            $.ajax({
                type: "POST",
                url: base_url_siat('operaciones/cierrePuntoVenta'),
                dataType: "json",
                data: {
                    "cliente": {
                        "cuis":this.cuis,
                        "codigoSucursal": this.codigoSucursal,
                        "codigoPuntoVenta": this.codigoPuntoVenta,
                    }
                },
            }).done(function (res) {
                quitarcargando()
                
                res = res.RespuestaCierrePuntoVenta
                if (res.transaccion) {
                    quitarcargando()
                    $.ajax({
                        type: "post",   
                        url: base_url('index.php/siat/codigos/Cuis/cierrePuntoVenta'),
                        dataType: "json",   
                        data: {
                            cuis: app.cuis,
                            codigoSucursal: app.codigoSucursal,
                            codigoPuntoVenta: app.codigoPuntoVenta,
                        },                                    
                    }).done(function(res){
                            console.log(res);
                            /* swal({
                                title: 'PUNTO CERRADO!',
                                text: "El Punto de venta fue cerrado correctamente.",
                                type: 'success', 
                                showCancelButton: false,
                            })
                            return */ 
                    }) 
                    swal({
                        title: 'PUNTO CERRADO',
                        html: `El punto de venta: ${res.codigoPuntoVenta} fue cerrado.`,
                        type: 'success',
                        showCancelButton: false,
                        allowOutsideClick: false,
                    })

                    




                    $("#modalCierre").modal("hide");
                    app.get_putnosVenta()

                } else {
                    quitarcargando()
                    swal({
                        title: 'ERROR',
                        html: `${res.mensajesList.descripcion}`,
                        type: 'error',
                        showCancelButton: false,
                        allowOutsideClick: false,
                    })
                }
               console.log(res.RespuestaRegistroPuntoVenta);

            }).fail(function (jqxhr, textStatus, error) {
                let err = textStatus + ", " + error;
                console.log("Request Failed: " + err);
            });

        }
    },
})