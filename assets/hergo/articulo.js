/**
 * Módulo de Artículos
 * Gestiona la interfaz de usuario para visualizar, crear, editar y eliminar artículos.
 */

// FileUtils se usa para gestionar URLs de imágenes en DigitalOcean Spaces
// La configuración se centraliza en esta clase para mantener consistencia en toda la app

function permisoArticulos() {
    (!checkAuth(46)) ? $('#btnCrear').addClass('hide') : $('#btnCrear').removeClass('hide')
}

$(document).ready(function(){
    // Inicializar la tabla de artículos
    retornarTabla();
    
    // Verificar permisos
    permisoArticulos();
    
    // Configurar validador del formulario
    formItemValidator();
    
    // Configurar selector de archivos para imágenes usando FileUtils
    FileUtils.setupFileInput("#imagenes", {
        maxFileSize: 1024,
        allowedFileExtensions: ['jpg', 'jpeg', 'png', 'gif']
    });
    
    // Configurar manejo de eliminación de imágenes usando FileUtils
    FileUtils.handleFileClear('#imagenes', '#imagenEliminada');
})
$(document).on("change", "#is_active", function () {
    retornarTabla();
})
$(document).on("change", "#codigoActividadSiat", function () {
    let codigo = $("#codigoActividadSiat").val()
    const $select = $("#codigoSiatSelect");
    $select.empty();
    getCodigosSiat(codigo);
})
function getCodigosSiat(codigo) {
    $.ajax({
        url: base_url("index.php/Articulos/getCodigosSiat"),
        dataType: "json",
        type: 'POST',
        data: {
            codigoActividad: codigo
        },
    }).done(function (res) {
            const $select = $("#codigoSiatSelect");
            Object.entries(res).forEach(([key, value]) => {
                //console.log(key);
                $select.append($("<option>", {
                    value: value.codigoProducto,
                    text: value.codigoProducto + ' | ' + value.descripcionProducto
                }))
              });
    }).error(function (res) {
        console.log(res);
    });
}
function formItemValidator() {
    $('#form_articulo').bootstrapValidator({
        feedbackIcons: {
        valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh',
        },
        excluded: ':disabled',
        fields: {
                codigo: {
                    validators: {
                            /*stringLength: {
                            min: 6,
                            max: 6,
                            message: 'Ingrese cógigo válido'
                        },*/
                        notEmpty: {
                            message: 'Campo obligatorio'
                        },
                        /*regexp: {
                            regexp: /[A-Z]{2}[0-9]{4}/,
                            message: 'Formato de código incorrecto'
                        }*/
                    }
                },
                descripcion: {
                    validators: {
                            stringLength: {
                            min: 3,
                            message: 'Ingrese descrición válida'
                            
                        },
                    notEmpty: {
                            message: 'Campo obligatorio'
                        }
                            
                    }
                },
                descripcionFabrica: {
                    validators: {
                            stringLength: {
                            min: 2,
                            message: 'Ingrese descrición válida'
                            
                        },
                    }
                },
                unidad: {
                    validators: {
                        notEmpty: {
                            message: 'Campo obligatorio'
                        }
                    }
                },
                codigoActividadSiat: {
                    validators: {
                        notEmpty: {
                            message: 'Campo obligatorio'
                        }
                    }
                },
                codigoSiatSelect: {
                    validators: {
                        notEmpty: {
                            message: 'Campo obligatorio'
                        }
                    }
                },
                marca: {
                    validators: {
                    notEmpty: {
                            message: 'Campo obligatorio'
                        }
                        
                    }
                },
                linea: {
                    validators: {
                    notEmpty: {
                            message: 'Campo obligatorio'
                        }
                        
                    }
                },
                parte: {
                    validators: {
                        stringLength: {
                            min: 1,
                        },
                    }
                },
                posicion: {
                    validators: {                 
                        stringLength: {
                            min: 1,
                        },
                    }
                },
                autoriza: {
                    validators: {
                    notEmpty: {
                            message: 'Campo obligatorio'
                        }
                        
                    }
                },
                proser: {
                    validators: {
                    notEmpty: {
                            message: 'Campo obligatorio'
                        }
                        
                    }
                },
                uso: {
                    validators: {
                    notEmpty: {
                            message: 'Elija si articulo esta en uso'
                        }                    
                    }
                },             
                imagen: {
                    validators: {
                        file: {
                            //extension: 'jpeg,jpg,png',
                            type: 'image/jpeg,image/png',
                            maxSize: 2097152,   // 2048 * 1024
                            //message: 'Archivo no válido'
                        }
                    }
                },
                web:{
                    validators: {
                        notEmpty: {
                            message: 'Campo obligatorio'
                        }
                    }
                }
        }
    }).on('success.form.bv', function(e) {
        e.preventDefault();
        //let valuesToSubmit = $("#form_articulo").serialize();  
        let formData = new FormData($('#form_articulo')[0]);  
        /* for(let pair of formData.entries()) { console.log(pair[0]+ ', '+ pair[1]); }
        return */
        $.ajax({
                url: base_url("index.php/Articulos/addItem"),
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                dataType: "json",
        }).done(function (res) {
                console.log(res);
                if(res.status == true) {
                    $('#modalarticulo').modal('hide');
                    resetForm('#form_articulo')
                    swal(
                        'Artículo guardado',
                        `El articulo ${res.item.CodigoArticulo} fue ${res.msg} exitosamente.`,
                        'success'
                        )
                    retornarTabla()
                }
        }).error(function (res) {
            console.log(res);
            swal(
                'Error',
                'El código de artículo ya se encuentra registrado en nuestra bases de datos. <br> ' + res.status,
                'error'
            )
        });
     
    });
}

/********MODAL ALMACEN EDITAR**********/
$(document).on("click",".btnnuevo",function(){
    resetForm('#form_articulo')
    $(".modal-title").html("Agregar articulo")
    $("#bguardar_articulo").html("Guardar")
    /****resetinput****/
    $('#imagenes').fileinput('destroy');
    $("#imagenes").fileinput({
        language: "es",
        showUpload: false,
        previewFileType: "image",
        maxFileSize: 1024,
      
    });
    $('#imagenes').fileinput('refresh');
    $('#imagenEliminada').val(0); // Reset the hidden field
})
$(document).on("click",".botoncerrarmodal",function(){
   resetForm('#form_articulo')
})

function mostrarModal(fila)
{
    //console.log(fila);
    getCodigosSiat(fila.codigoCaeb)
    cargarimagen(fila.Imagen, fila.ImagenUrl)
    $("#id_articulo").val(fila.idArticulos)
    $("#codigoarticulo").val(fila.CodigoArticulo)
    $("#descrpcionarticulo").val(fila.Descripcion)
    $("#descripcionFabrica").val(fila.detalleLargo)
    asignarselect(fila.Unidad,$("#unidadarticulo"))
    asignarselect(fila.Marca,$("#marcaarticulo"))
    asignarselect(fila.Linea,$("#lineaarticulo"))
    asignarselect(fila.actividadCaeb,$("#codigoActividadSiat"))
    asignarselect(fila.codigoDescProductoSiat,$("#codigoSiatSelect"))

    $("#partearticulo").val(fila.NumParte)
    $("#precio").val(fila.precio)
    $("#arancelariaarticulo").val(fila.PosicionArancelaria)
    asignarselect(fila.Requisito,$("#autorizaarticulo"))
    $("#uso").val(fila.EnUso)
    $("#web").val(fila.web_catalogo)
    $("#productoarticulo").val(fila.ProductoServicio)
    $(".modal-title").html("Modificar articulo")
    $("#bguardar_articulo").html("Modificar")
    $("#modalarticulo").modal("show");
  
}

function retornarTabla()
{
    uso = $("#is_active").val()
    agregarcargando();

    $.getJSON({
        type:"POST",
        url: base_url('index.php/Articulos/mostrarArticulos'),
        dataType: "json",
        data: {uso: uso},
    }).done(function(res){
        quitarcargando();
        $("#tarticulo").bootstrapTable('destroy');
        
        $("#tarticulo").bootstrapTable({
            
            data:res,           
            striped:true,
            //pagination:false,
            //pageSize:1000,
            clickToSelect:true,
            search:true,
            height:700,
            showExport:true,
            showColumns: true,
            exportTypes:['excel','csv'],
            exportDataType:'all',
            exportOptions:{fileName: 'Articulos',worksheetName: "Articulos"},
            columns:[
            {   
                field: 'idArticulos',            
                title: 'id',
                align: 'center',
                sortable:true,
            },  
            {   
                field: 'Imagen',            
                title: 'Imagen',
                align: 'center',
                searchable: false,
                formatter: mostrarimagen
            },         
            {
                field:'CodigoArticulo',
                title:"Código",
                sortable:true,
                align: 'center',
            },
            {
                field:"codigoCaeb",
                title:"codigoCaeb",
                sortable:true,
                align: 'center',
            },
            {
                field:"codigoProductoSiat",
                title:"codigoProductoSiat",
                sortable:true,
                align: 'center',
            },
            {
                field:'Descripcion',
                title:"Descripcion",
                sortable:true,
                align: 'left',
            },
            {
                field:'descripcionFabrica',
                title:"Descripcion Fabrica",
                sortable:true,
                align: 'left',
            },
            {
                field:'Unidad',
                title:"Unidad",
                sortable:true,
                searchable: false,
                align: 'center',
            },
            {
                field:"Marca",
                title:"Marca",
                sortable:true,
                searchable: false,
                align: 'center',
            },
            {
                field:"Linea",
                title:"Linea",
                sortable:true,
                searchable: false,
                align: 'center',
            },
            {
                field:"precio",
                title:"Precio BOB",
                sortable:true,
                searchable: false,
                align: 'right',
                formatter: formato_moneda,
                visible:true
            },
            {
                field:"NumParte",
                title:"Nro. de Parte",
                sortable:true,
                visible:true
            },
            {
                field:"PosicionArancelaria",
                title:"Posicion<br>Arancelaria",
                sortable:true,
            },
            {
                field:"Requisito",
                title:"Requisito",
                sortable:true,
                visible:false
            },
            {
                field:"ProductoServicio",
                title:"Producto <br> Servicio",
                formatter:verproductoservicio,
                sortable:true,
                visible:false
            },
            {
                field:"Fecha",
                title:"Fecha",
                sortable:true,
                visible:false,
                searchable: false,
                formatter: formato_fecha
            },          
            {
                field:"autor",
                title:"Autor",
                sortable:true,
                visible:false,
                searchable: false,
            },          
            {               
                title: 'Editar',
                align: 'center',
                events: operateEvents,
                visible:(!checkAuth(69)) ? false :true,
                searchable:false,
                formatter: operateFormatter
            }]
        });

        $("#tarticulo").bootstrapTable('hideLoading');
        //$("#tarticulo").bootstrapTable('resetView');
        
    }).fail(function( jqxhr, textStatus, error ) {
    var err = textStatus + ", " + error;
    console.log( "Request Failed: " + err );
    });
}
/**
 * Renderiza la columna de imagen en la tabla de artículos
 * 
 * @param {string} value - Nombre de la imagen (vieja estructura)
 * @param {Object} row - Fila completa de datos del artículo
 * @param {number} index - Índice de la fila
 * @returns {string} HTML para mostrar la imagen en la tabla
 */
function mostrarimagen(value, row, index)
{
    let ruta = "";
    let clase = "imagenminiatura";
    let baseSpacesUrl = "https://images.hergo.app/"; // URL base para imágenes en Spaces
    
    // Determinar la ruta de la imagen
    if (row.ImagenUrl) {
        // Construir la URL completa con la base de Spaces
        ruta = baseSpacesUrl + row.ImagenUrl;
    }
    else if (value && value !== "") {
        // Compatibilidad con imágenes antiguas
        ruta = base_url("/assets/img_articulos/" + value);
    }
    else {
        // Imagen predeterminada
        ruta = base_url("/assets/img_articulos/hergo.jpg");
        clase = ""; // Sin clase para imagen predeterminada
    }
    
    // Construir el HTML para la miniatura
    let imagen = '<div class="contimg"><img src="' + ruta + '" class="' + clase + '"></div>';
    
    return imagen;
}

function verproductoservicio(value, row, index)
{
    if(value=="p")
        return ['Producto'].join('');
    else
        return ['Servicio'].join('');
}
function operateFormatter(value, row, index) 
{
    if (checkAuth(69)) {
        return [
            '<a class="editar" title="editar" data-toggle="modal" data-target="#editar" style="cursor:pointer">',
            '<i class="fa fa-pencil"></i>',
            '</a>  '       
        ].join('');
    }
    
}
/***********Eventos*************/
window.operateEvents = {
    'click .editar': function (e, value, row, index) {
        //resetForm('#form_articulo')
        //console.log(row);
      //  alert('You click like action, row: ' + JSON.stringify(row));
      mostrarModal(row)
      $("#tarticulo").bootstrapTable('hideLoading');            
    }
};
$(document).on("click",".imagenminiatura",function(){    
    rutaimagen=$(this).attr('src')
    var imagen='<img class="maximizada" src="'+rutaimagen+'">'
    $("#imagen_max").html(imagen)
    $("#prev_imagen").modal("show");
})

/**
 * Carga una imagen en el componente de entrada de archivo
 * 
 * @param {string} imagen - Nombre de la imagen (para compatibilidad con imágenes locales)
 * @param {string} imagenUrl - Ruta relativa de la imagen en Spaces (si existe)
 */
function cargarimagen(imagen, imagenUrl)
{
    let existingImagePath = null;
    
    // Determinar la ruta correcta de la imagen
    if (imagenUrl) {
        // Usar la URL relativa de Spaces
        existingImagePath = imagenUrl;
    }
    else if (imagen && imagen !== "") {
        // Compatibilidad con imágenes antiguas (ruta completa)
        existingImagePath = base_url("/assets/img_articulos/" + imagen);
    }
    
    // Configurar el componente fileinput usando FileUtils
    FileUtils.setupFileInput("#imagenes", {
        showUpload: false,
        previewFileType: "image",
        maxFileSize: 1024
    }, existingImagePath);
    
    // FileUtils ya maneja el estado del campo oculto para eliminación
}

/**
 * Obtiene los detalles completos de un artículo usando AJAX
 * 
 * @param {number} id - ID del artículo
 * @returns {Promise} - Promesa que resuelve con los detalles del artículo
 */
function getArticuloById(id) {
    return new Promise((resolve, reject) => {
        $.ajax({
            url: base_url("index.php/Articulos/getArticuloDetails"),
            dataType: "json",
            type: 'POST',
            data: {
                id: id
            },
        }).done(function(articulo) {
            resolve(articulo);
        }).fail(function(error) {
            console.error("Error al obtener detalles del artículo:", error);
            reject(error);
        });
    });
}

