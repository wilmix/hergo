$(document).ready(function () {
	$.fn.dataTable.ext.errMode = 'none';
    getLevels()
})
function getLevels() {
	getLevel1()
    getLevel2()
    getLevel3()
}
let lengtMenu = [
	[10, 25, 50, -1],
	['10 filas', '25 filas', '50 filas', 'Todo']
]
let columns = [
	{
		data: 'id',
		title: 'id',
		className: 'text-center',
		visible: false
	},
	{
		data: 'name',
		title: 'Nombre',
		className: 'text-left',
	},
	{
		data: 'description',
		title: 'Descripcion',
		className: 'text-left',
	},
	{
		data: 'img',
		title: 'Imagen',
		className: 'text-left',
		render: mostrarimagen,
	},
	{
		data: 'is_active',
		title: 'Activo',
		className: 'text-center',
	},
	{
		data: 'autor',
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
		data: null,
		title: '',
		width: '120px',
		className: 'text-center',
		render: buttons
	},
]
function buttonsTable(table) {
	return [
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
						nivel1.state.clear()
						nivel2.state.clear()
						nivel3.state.clear()
						getLevels()
					}
				},
	
			]
		},
		{
			text: '<i class="fas fa-sync" aria-hidden="true" style="font-size:18px;"></i>',
			action: function (e, dt, node, config) {
				getLevels()
			}
		},
		{
			text: '<i class="fa fa-plus add" aria-hidden="true" style="font-size:18px;"></i>',
			action: function (e, dt, node, config) {
				modal.showModal(table)
			}
		},
	]
}
function getLevel1() {
	$.ajax({
		type: "POST",
		url: base_url('index.php/web/ConfigArticulosWeb/getLevel1'),
		dataType: "json",
		data: {
            table: 'web_nivel1'
		},
	}).done(function (res) {
		nivel1 = $('#web_nivel1').DataTable({
			data: res,
			destroy: true,
            searching: false,
			dom: 'Bfrtip',
			responsive: true,
			lengthMenu: lengtMenu,
			pageLength: 5,
			columns: columns,
			stateSave: true,
			stateSaveParams: function (settings, data) {
				data.order = []
			},
			buttons: buttonsTable('web_nivel1'),
			order: [],
			fixedHeader: {
				header: true,
				footer: true
			},
			//paging: false,
			language: datatableLangage,

		});
		quitarcargando();
	}).fail(function (jqxhr, textStatus, error) {
		let err = textStatus + ", " + error;
		console.log("Request Failed: " + err);
	});
}
function getLevel2() {
	$.ajax({
		type: "POST",
		url: base_url('index.php/web/ConfigArticulosWeb/getLevel1'),
		dataType: "json",
		data: {
            table: 'web_nivel2'
		},
	}).done(function (res) {
		nivel2 = $('#web_nivel2').DataTable({
			data: res,
			destroy: true,
            searching: false,
			dom: 'Bfrtip',
			responsive: true,
			lengthMenu: lengtMenu,
			pageLength: 5,
			columns: columns,
			stateSave: true,
			stateSaveParams: function (settings, data) {
				data.order = []
			},
			buttons: buttonsTable('web_nivel2'),
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
}
function getLevel3() {
	$.ajax({
		type: "POST",
		url: base_url('index.php/web/ConfigArticulosWeb/getLevel1'),
		dataType: "json",
		data: {
            table: 'web_nivel3'
		},
	}).done(function (res) {
		nivel3 = $('#web_nivel3').DataTable({
			data: res,
			destroy: true,
            searching: false,
			dom: 'Bfrtip',
			responsive: true,
			lengthMenu: lengtMenu,
			pageLength: 5,
			columns: columns,
			stateSave: true,
			stateSaveParams: function (settings, data) {
				data.order = []
			},
			buttons: buttonsTable('web_nivel3'),
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
}
function mostrarimagen(value, row, index)
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
        ruta="https://images.hergo.app/web/levels/level-1/"+value
    }

    imagen = '<div class="contimg"><img src="'+ruta+'" class="'+clase+'"></div>'
    return [imagen].join('')
}

function buttons (data, type, row) {
    return `
        <button type="button" class="btn btn-default edit_${row.level}">
            <span class="fa fa-pencil" aria-hidden="true">
            </span>
        </button>
    `
}
$(document).on("click", "button.edit_web_nivel1", function () {
    let row = getRow(nivel1, this)
	modal.edit(row)
})
$(document).on("click", "button.edit_web_nivel2", function () {
    let row = getRow(nivel2, this)
	modal.edit(row)
})
$(document).on("click", "button.edit_web_nivel3", function () {
    let row = getRow(nivel3, this)
	modal.edit(row)
})

const modal = new Vue({
	el: '#app',
	data: {
        id : 0,
		name : '',
		description: '',
        isActive: 1,
        modalTitle: 'Añadir a Nivel ',
        nameTable: '',
		dataNivel1: []
	},
	methods:{
		showModal(nameTable){
            this.modalTitle = 'Añadir a Nivel '
            let regex = /(\d+)/g
            this.nameTable = nameTable
            number = parseInt(nameTable.match(regex))
            this.modalTitle = this.modalTitle + number
            $("#levelModal").modal("show");
        },
        add(e){
            agregarcargando()
            e.preventDefault()
            if (!this.name) {
                quitarcargando()
                swal({
                    title: 'Error',
                    text: "Por favor llene correctamente el formulario",
                    type: 'error', 
                    showCancelButton: false,
                })
                return
            }
			let formData = new FormData($('#formModalNiveles')[0])
			formData.append('table', this.nameTable)
			formData.append('id', this.id)
			/* for(let pair of formData.entries()) {
				console.log(pair[0]+ ', '+ pair[1]); 
			} 
			quitarcargando()
			return */

            $.ajax({
                url: base_url('index.php/web/ConfigArticulosWeb/addLevel'),
                type: "POST",      
                data: formData,    
				cache: false,
				contentType: false,
				processData: false,
				dataType: "json" 
              }).done(function(res){
				console.log(res);
                modal.clear()
                $("#levelModal").modal("hide");
                quitarcargando()
				location.reload();
              }) 
        },
        clear(){
            this.name = ''
			this.description = ''
            this.isActive = 1
            this.modalTitle = 'Añadir a Nivel '
        },
        edit(row){

            this.id = row.id
            this.name = row.name
			this.description = row.description
            this.isActive = row.is_active
            this.showModal(row.level)
        },
	}
})