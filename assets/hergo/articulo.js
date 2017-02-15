$(document).ready(function(){
    retornarTabla()
    $("#imagenes").fileinput({
        language: "es",
        showUpload: false,
        previewFileType: "image",
        maxFileSize: 1024,
      
    });
    
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
                        stringLength: {
                        min: 6,
                        max: 6,
                        message: 'Ingrese cógigo válido'
                    },
                    notEmpty: {
                        message: 'Campo obligatorio'
                    }
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
            unidad: {
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
                        min: 3,
                    },
                }
            },
            posicion: {
                validators: {                 
                    between: {
                        min: 0,
                        max: 9999999999,
                        message: 'Ingrese Posicion Arancelaria Valida'
                    }
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
           }
        })
        .on('success.form.bv', function(e) {
            e.preventDefault();
            var valuesToSubmit = $("#form_articulo").serialize();  
            var formData = new FormData($('#form_articulo')[0]);  
            console.log(formData)      
        
           /* retornarajax(base_url("index.php/articulos/agregarArticulo"),valuesToSubmit,function(data)
            {
                estado=validarresultado_ajax(data);
                if(estado)
                {                       
                    $(".mensaje_ok").html(" Los datos se guardaron correctamente");
                    //$("#modal_ok").modal("show");
                    $('#contact-form-success').show().fadeOut(10000);
                    $('#modalarticulo').modal('hide');
                    retornarTabla();              
                }
            }) */
            $.ajax({
                url: base_url("index.php/articulos/agregarArticulo"),
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (returndata) {
                   $(".mensaje_ok").html(" Los datos se guardaron correctamente");
                    $("#modal_ok").modal("show");
                    $('#contact-form-success').show().fadeOut(10000);
                    $('#modalarticulo').modal('hide');
        
                    retornarTabla();
                }
            }); 
        });
});/**FIN READY**/
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
})
$(document).on("click",".botoncerrarmodal",function(){
   resetForm('#form_articulo')
})

function asignarselect(text1,select)
{
    $("option",select).filter(function() {
        var aux=$(this).text()
        return aux.toUpperCase() == text1.toUpperCase();
    }).prop('selected', true);
}

function mostrarModal(fila)
{
    console.log(fila)
    cargarimagen(fila.Imagen)
    $("#id_articulo").val(fila.idArticulos)
    $("#codigoarticulo").val(fila.CodigoArticulo)
    $("#descrpcionarticulo").val(fila.Descripcion)
    asignarselect(fila.Unidad,$("#unidadarticulo"))
    asignarselect(fila.Marca,$("#marcaarticulo"))
    asignarselect(fila.Linea,$("#lineaarticulo"))
    $("#partearticulo").val(fila.NumParte)
    $("#arancelariaarticulo").val(fila.PosicionArancelaria)
    asignarselect(fila.Requisito,$("#autorizaarticulo"))
    $("#uso").val(fila.EnUso)

    $("#productoarticulo").val(fila.ProductoServicio)
    $(".modal-title").html("Editar articulo")
    $("#bguardar_articulo").html("Editar")
    $("#modalarticulo").modal("show");
  
}
function retornarTabla()
{
    $.ajax({
        type:"POST",
        url: base_url('index.php/articulos/mostrarArticulos'),
        dataType: "json",
        data: {},
    }).done(function(res){

        $("#tarticulo").bootstrapTable('destroy');
        $("#tarticulo").bootstrapTable({
            
            data:res,           
            striped:true,
            pagination:true,
            pageSize:25,
            clickToSelect:true,
            search:true,
            showExport:true,
            exportTypes:['excel','csv'],
            exportDataType:'all',
            exportOptions:{fileName: 'Articulos',worksheetName: "Articulos"},
            columns:[
            {   
                field: 'idArticulos',            
                title: 'id',
                align: 'center',
                //events: operateEvents,
            },  
            {   
                field: 'Imagen',            
                title: 'Imagen',
                align: 'center',
                //events: operateEvents,
                formatter: mostrarimagen
            },         
            {
                field:'CodigoArticulo',
                title:"Código",
                sortable:true,
            },
            {
                field:'Descripcion',
                title:"Descripcion",
                sortable:true,
            },
            {
                field:'Unidad',
                title:"Unidad",
                sortable:true,
            },
            {
                field:"Marca",
                title:"Marca",
                sortable:true,
            },
            {
                field:"Linea",
                title:"Linea",
                sortable:true,
            },
            {
                field:"NumParte",
                title:"Nro. de Parte",
                sortable:true,
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
            },
            {
                field:"ProductoServicio",
                title:"Producto <br> Servicio",
                formatter:verproductoservicio,
                sortable:true,
            },
            {
                field:"fecha",
                title:"Fecha",
                sortable:true,
                visible:false,
                formatter: formato_fecha
            },          
            {
                field:"autor",
                title:"Autor",
                sortable:true,
                visible:false,
            },          
            {               
                title: 'Editar',
                align: 'center',
                events: operateEvents,
                formatter: operateFormatter
            }]
        });

        $("#tarticulo").bootstrapTable('hideLoading');
        $("#tarticulo").bootstrapTable('resetView');
        
    }).fail(function( jqxhr, textStatus, error ) {
    var err = textStatus + ", " + error;
    console.log( "Request Failed: " + err );
    });
    //$("body").css("padding-right","0px");
}
function mostrarimagen(value, row, index)
{
    //value
    var ruta=""
    var imagen=""
    if((value=="")||(value=="null"))
    {
        ruta="/assets/img_articulos/ninguno.png"
        clase=""
    }
    else
    {
        clase="imagenminiatura"
        ruta="/assets/img_articulos/"+value
    }

    imagen = '<div class="contimg"><img src="'+base_url(ruta)+'" class="'+clase+'"></div>'
    return [imagen].join('');
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
    return [
        '<a class="editar" title="editar" data-toggle="modal" data-target="#editar" style="cursor:pointer">',
        '<i class="fa fa-pencil"></i>',
        '</a>  '       
    ].join('');
}
/***********Eventos*************/
window.operateEvents = {
    'click .editar': function (e, value, row, index) {
      //  alert('You click like action, row: ' + JSON.stringify(row));
      resetForm('#form_articulo')
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

function cargarimagen(imagen)
{
     ruta=(imagen=="")?"/assets/img_articulos/ninguno.png":"/assets/img_articulos/"+imagen
    $('#imagenes').fileinput('destroy');
    console.log(base_url(ruta))
     $("#imagenes").fileinput({
        initialPreview: [
           
            base_url(ruta)
        ],
        initialPreviewAsData: true,
        
        initialCaption: imagen,
        language: "es",
        showUpload: false,
        previewFileType: "image",
        maxFileSize: 1024,
    });
     $('#imagenes').fileinput('refresh');
     console.log("refresh")
}



