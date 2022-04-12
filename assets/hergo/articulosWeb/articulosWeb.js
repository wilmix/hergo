

function showImage(value, row, index, route, xxx)
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
        ruta="https://images.hergo.app/web/levels/"+value
    }

    imagen = '<div class="contimg"><img src="'+ruta+'" class="'+clase+'"></div>'
    return [imagen].join('')
}
$(document).on("click", "button.edit", function () {
    let row = getRow(table, this)
	web.edit(row)
})

Vue.component("v-select", VueSelect.VueSelect);
const web = new Vue({
	el: '#app',
	data: {
		data_n1: [],
		data_n2: [],
		data_n3: [],
		articulo_id:0,
		codigo:'',
		titulo: '',
		descripcion: '',
		desc_sis:'',
		n1: {},
		n2: {},
		n3: {},
		id: 0
	},
	mounted() {
		this.getItems()
		this.getData()
	},
	methods:{
		getItems(){
			$.fn.dataTable.ext.errMode = 'none';
			$.ajax({
				type: "POST",
				url: base_url('web/ArticulosWeb/getItems'),
				dataType: "json"
			}).done(function (res) {
				table = $('#web').DataTable({
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
							data: 'articulo_id_sis',
							title: 'articulo_id',
							className: 'text-center',
							visible: false
						},
						{
							data: 'codigo_sis',
							title: 'Codigo Sis',
							className: 'text-center',
						},
						{
							data: 'descripcion_sis',
							title: 'Descripción Sis',
							className: 'text-center',
						},
						{
							data: 'titulo',
							title: 'Título',
							className: 'text-right',
						},
						{
							data: 'descripcion',
							title: 'Descripción',
							className: 'text-right',
						},
						{
							data: 'n1',
							title: 'Nivel 1',
						},
						{
							data: 'n2',
							title: 'Nivel 2',
						},
						{
							data: 'n3',
							title: 'Nivel 3',
						},
						{
							data: 'created_by',
							title: 'AUTOR',
							className: 'text-right',
							sorting: false,
							visible: false
						},
						{
							data: 'created_at',
							title: 'CREADO EN',
							className: 'text-center',
							render: formato_fecha_corta,
							visible: false
						},
						{
							data: 'img_sis',
							title: 'Imagen Sis',
							className: 'text-left',
							render: function (data, type, row) {
								return web.showImage(data,'img_sis')
							}
						},
						{
							data: 'img_web',
							title: 'Imagen Web',
							className: 'text-left',
							render: function (data, type, row) {
								return web.showImage(data,'img_web')
							}

						},
						{
							data: null,
							title: '',
							width: '120px',
							className: 'text-center',
							render: function () {
								return `
									<button type="button" class="btn btn-default edit">
										<span class="fa fa-pencil" aria-hidden="true">
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
								web.getItems()
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
										web.getItems()
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
					language: datatableLangage,
				});
				quitarcargando();
			}).fail(function (jqxhr, textStatus, error) {
				let err = textStatus + ", " + error;
				console.log("Request Failed: " + err);
			});
		},
		showImage(data, field){
			data =encodeURI(data)
			imgVacio = "/assets/img_articulos/hergo.jpg"
			clase="imagenminiatura"
				if (field == 'img_sis') { 
					ruta = !data || data =='null' ? imgVacio : base_url("assets/img_articulos/") + data 
				} else if (field == 'img_web'){
					ruta = data == 'null' ? imgVacio : "https://images.hergo.app/web/items/" + data 
				}
			let imagen = '<div class="contimg"><img src="'+ruta+'" class="'+clase+'"></div>'
			return [imagen].join('')
		},
		edit(row){
			//console.log(row);
			this.loadImg(row.img_web)
			this.id = row.id 
			this.articulo_id = row.articulo_id_sis
			this.titulo = row.titulo
			this.descripcion = row.descripcion
			this.n1 = {id:row.n1_id,label:row.n1}
			this.n2 = {id:row.n2_id,label:row.n2}
			this.n3 = {id:row.n3_id,label:row.n3}
			this.codigo = row.codigo_sis
			this.desc_sis = row.descripcion_sis
			$("#itemWeb").modal("show");
		},
		loadImg(img){
			ruta = img ? "https://images.hergo.app/web/items/"+img : base_url('assets/img_articulos/ninguno.png')
			$('#imagen').fileinput('destroy');
			$("#imagen").fileinput({
				initialPreview: [
					ruta
				],
				initialPreviewAsData: true,
				initialCaption: img,
				language: "es",
				showUpload: false,
				previewFileType: "image",
				maxFileSize: 1024,
			});
			$('#imagen').fileinput('refresh');
		},
		getData(){
			$.ajax({
				type: "POST",
				url: base_url('index.php/web/ArticulosWeb/getData'),
				dataType: "json"
			}).done(function (res) {
				web.data_n1 = res.n1
				//web.data_n2 = res.n2
				//web.data_n3 = res.n3
			})
		},
		add(e){
            //agregarcargando()
            e.preventDefault()
            if (!this.titulo || !this.descripcion ) {
                quitarcargando()
                swal({
                    title: 'Error',
                    text: "Por favor llene correctamente el formulario",
                    type: 'error', 
                    showCancelButton: false,
                })
                return
            }
			let formData = new FormData($('#formItemWeb')[0])
			formData.append('id', this.id ? this.id : 0)
			formData.append('articulo_id', this.articulo_id)
			formData.append('id_nivel1', this.n1.id ? this.n1.id : 0 )
			formData.append('id_nivel2', this.n2.id ? this.n2.id : 0 )
			formData.append('id_nivel3', this.n3.id ?this.n3.id : 0 )

			/* for(let pair of formData.entries()) {
				console.log(pair[0]+ ', '+ pair[1]); 
			} 
			quitarcargando()
			return */
 			
            $.ajax({
                url: base_url('index.php/web/ArticulosWeb/addItemWeb'),
                type: "POST",      
                data: formData,    
				cache: false,
				contentType: false,
				processData: false,
				dataType: "json" 
            }).done(function(res){
                $("#itemWeb").modal("hide");
                quitarcargando()
				web.getItems()
            }) 
        },
		getLevel(n, table, where){
			if (table == 'web_nivel2') {
				web.n2 = {}
				web.n3 = {}
			} else if(table == 'web_nivel3'){
				web.n3 = {}
			} 
			$.ajax({
				type: "POST",
				url: base_url('index.php/web/ArticulosWeb/getLevel'),
				dataType: "json",
				data: {
					level: n,
					table: table,
					where: where
				}
			}).done(function (res) {
				if (table == 'web_nivel2') {
					web.data_n2 = res
				} else if(table == 'web_nivel3'){
					web.data_n3 = res
				}
			})
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
		firma() {
			localStorage.firma = this.firma
		}
	}
})