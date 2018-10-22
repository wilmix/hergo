$(document).ready(function(){
  
     $('#form_marcaArticulos').bootstrapValidator({
             
        feedbackIcons: {
        valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
        },
        excluded: ':disabled',
        fields: {          
           marca: {
                validators: {
                        stringLength: {
                        min: 2,
                        message: 'Ingrese nombre de marca válido'
                        
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
            var valuesToSubmit = $("#form_marcaArticulos").serialize();
            console.log(valuesToSubmit)
            $.ajax({
                url: base_url("index.php/MarcaArticulos/agregarMarca"),
                data: valuesToSubmit,              
                type: 'POST',
            })
            .done(function( data, textStatus, jqXHR ) {
                if ( console && console.log ) {
                    console.log( "La solicitud se ha completado correctamente." );                    
                    $('#contact-form-success').show().fadeOut(10000);
                    $('#modalmarca').modal('hide');
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
    $("#form_marcaArticulos")[0].reset();
    enivardatosmodalalmacen(this)
})
$(document).on("click",".btnnuevo",function(){
     $("#form_marcaArticulos")[0].reset();
})
function borrarmodal()
{
    $(".modalmarcatitulo").html("Agregar Marca")
    $("#modalnombremarca").val("")
    $("#modalsiglamarca").val("")
    $("#bguardar_marca").html("Guardar")
}
function enivardatosmodalalmacen(id)
{
    fila=$(id).parents("tr")
    cod=$(fila).attr("id");
    datos=$(fila).find("td")
    marca=$(datos[0]).html();
    sigla=$(datos[1]).html();
    $("#cod_marca").val(cod)
    $(".modalmarcatitulo").html("Editar Marca")
    $("#modalnombremarca").val(marca)
    $("#modalsiglamarca").val(sigla)
    $("#bguardar_marca").html("Editar")
    $('#modalmarca').modal('show');
}

