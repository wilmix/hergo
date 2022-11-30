let columnsSincronizarActividades = [
    {
        data: 'codigoCaeb',
        title: 'codigoCaeb',
        className: 'text-center',
    },
    {
        data: 'descripcion',
        title: 'descripcion',
        className: 'text-center',
    },
    {
        data: 'tipoActividad',
        title: 'tipoActividad',
        className: 'text-center',
    },
]
Vue.component('v-select', VueSelect.VueSelect);
const sincro = new Vue({
	el: '#app',
	data: {
		catalogos: false,
        ultima: false

	},
    mounted() {
        this.getData()
	},
	methods:{
        getData(){
                agregarcargando()
                $.ajax({
                    type: "POST",
                    url: base_url_siat('lastSync'),
                    dataType: "json",
                }).done(function (res) {
                    quitarcargando()
                    data = JSON.parse(res.respuesta)
                    sincro.catalogos = data
                    sincro.ultima = moment(res.created_at).format('D MMMM YYYY, h:mm:ss a');
                });

        },
        sincronizarManual(){
            agregarcargando()
                $.ajax({
                    type: "post",   
                    url: base_url_siat('sincronizarAutomatico'),
                    dataType: "json",   
                    data: {
                            cuis: "268DE987",
                            codigoSucursal: "0",
                            codigoPuntoVenta: "0"
                    },                                    
                }).done(function(res){
                    if (res.id > 0) {
                        sincro.getData()
                        swal({
                            title: 'OK',
                            text: "La sincronización se realizó exitosamente",
                            type: 'success', 
                            showCancelButton: false,
                        })
                    } else {
                        swal({
                            title: 'Error ',
                            text: 'Hubo cambios en el catalogo favor revisar',
                            type: 'error', 
                            showCancelButton: false,
                        })
                    }
                }) 
           
        }
	},
  })