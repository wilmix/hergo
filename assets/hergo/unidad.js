$(document).ready(function(){
  
     $('#form_unidad').bootstrapValidator({
             
        feedbackIcons: {
        valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
        },
        excluded: ':disabled',
        fields: {          
           unidad: {
                validators: {
                        stringLength: {
                        min: 3,
                        max: 3,
                        message: 'Dede contener 3 caracteres'
                        
                    },
                    notEmpty: {
                        message: 'Campo obligatorio'
                    }
                        
                }
            },            
            sigla: {
                validators: {
                     stringLength: {
                        min: 2,
                        max: 2,
                        message: 'La sigla debe tener 2 caracteres'
                    },
                                    notEmpty: {
                        message: 'Campo obligatorio'
                    }
                }
            },                
           }
        })
        .on('success.form.bv', function(e) {
            // Prevent form submission
            e.preventDefault();
            // Get the form instance
            var valuesToSubmit = $("#form_unidad").serialize();
            //alert (valuesToSubmit);
            $.ajax({
                url: base_url("index.php/Unidad/agregarUnidad"),
                data: valuesToSubmit,              
                type: 'POST',
            })
            .done(function( data, textStatus, jqXHR ) {
                if ( console && console.log ) {
                    console.log( "La solicitud se ha completado correctamente." );                    
                    $('#contact-form-success').show().fadeOut(10000);
                    $('#modallinea').modal('hide');
                    document.location.href=""
                }
             })
            .fail(function( jqXHR, textStatus, errorThrown ) {
                if ( console && console.log ) {
                    console.log( "La solicitud a fallado: " +  textStatus);
                }
            });
        });
});/**FIN READY**/
/********MODAL ALMACEN EDITAR**********/
$(document).on("click",".botoneditar",function(){
    $("#form_unidad")[0].reset();
    enivardatosmodalalmacen(this)
})
$(document).on("click",".btnnuevo",function(){
     $("#form_unidad")[0].reset();
})
function borrarmodal()
{
    $(".modalunidadtitulo").html("Agregar Marca")
    $("#modalnombreunidad").val("")
    $("#modalsiglaunidad").val("")
    $("#bguardar_unidad").html("Guardar")
}
function enivardatosmodalalmacen(id)
{
    fila=$(id).parents("tr")
    cod=$(fila).attr("id");
    datos=$(fila).find("td")
    marca=$(datos[0]).html();
    sigla=$(datos[1]).html();
    $("#cod_unidad").val(cod)
    $(".modalunidadtitulo").html("Editar Marca")
    $("#modalnombreunidad").val(marca)
    $("#modalsiglaunidad").val(sigla)
    $("#bguardar_unidad").html("Editar")
    $('#modalunidad').modal('show');
    console.log(direccion)
}

