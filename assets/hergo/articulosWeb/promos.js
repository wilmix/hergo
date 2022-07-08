

function showImage(row)
{
    let ruta=""
    let imagen=""
    if((row.imagen=="")||(row.imagen==null))
    {
        ruta="/assets/img_articulos/hergo.jpg"
        clase=""
    }
    else
    {
        clase="imagenminiatura"
        ruta="https://images.hergo.app/web/promos/"+row.imagen
    }

    imagen = '<div class="contimg"><img src="'+ruta+'" class="'+clase+'"></div>'
    return [imagen].join('')
}
$(document).on("click", "button.edit", function () {
    let row = getRow(table, this)
	promos.edit(row)
})

Vue.component("v-select", VueSelect.VueSelect);
const promos = new Vue({
	el: '#app',
	data: {
		titulo: '',
		descripcion: '',
        isActive:1,
        imagen:'',
		id: 0
	},
	mounted() {
		this.getItems()
	},
	methods:{
		getItems(){
			$.fn.dataTable.ext.errMode = 'none';
			$.ajax({
				type: "POST",
				url: base_url('web/ArticulosWeb/getPromos'),
				dataType: "json"
			}).done(function (res) {
				table = $('#promos').DataTable({
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
							data: 'id',
							title: 'id',
							className: 'text-center',
							visible: false
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
							visible: true
						},
                        {
							data: 'is_active',
							title: 'Activo',
							className: 'text-right',
							visible: true
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
							data: 'imgagen',
							title: 'Imagen',
							className: 'text-left',
							render: function (data, type, row) {
								return showImage(row)
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
								promos.getItems()
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
										promo.getItems()
									}
								},
		
							]
						},
                        {
                            text: '<i class="fa fa-plus add" aria-hidden="true" style="font-size:18px;"></i>',
                            action: function (e, dt, node, config) {
                                promos.showModal()
                            }
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
        showModal(){
			this.clear()
			this.loadImg('')
            $("#itemWeb").modal("show");
        },
        clear(){
			this.id = 0
            this.titulo = ''
			this.descripcion = ''
            this.isActive = 1
			$('#img').fileinput('destroy');
        },
		edit(row){
			console.log(row);
			this.loadImg(row.imagen)
			this.id = row.id 
			this.titulo = row.titulo
			this.descripcion = row.descripcion
			this.isActive = row.is_active
			$("#itemWeb").modal("show");
		},
		loadImg(img){
			ruta = img ? "https://images.hergo.app/promos/"+img : base_url('assets/img_articulos/ninguno.png')
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
			//$('#imagen').fileinput('refresh');
		},
		add(e){
            agregarcargando()
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

			/* for(let pair of formData.entries()) {
				console.log(pair[0]+ ', '+ pair[1]); 
			} 
			quitarcargando()
			return */
 			
            $.ajax({
                url: base_url('index.php/web/ArticulosWeb/addItemPromo'),
                type: "POST",      
                data: formData,    
				cache: false,
				contentType: false,
				processData: false,
				dataType: "json" 
            }).done(function(res){
                $("#itemWeb").modal("hide");
                quitarcargando()
				promos.getItems()
            }) 
        },
	},
})