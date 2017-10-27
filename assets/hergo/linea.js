$(document).ready(function(){
  
     $('#form_linea').bootstrapValidator({
             
        feedbackIcons: {
        valid: 'glyphicon glyphicon-ok',
        invalid: 'glyphicon glyphicon-remove',
        validating: 'glyphicon glyphicon-refresh'
        },
        excluded: ':disabled',
        fields: {          
           linea: {
                validators: {
                        stringLength: {
                        min: 2,
                        message: 'Ingrese nombre de Linea válido'
                        
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
            var valuesToSubmit = $("#form_linea").serialize();
            //alert (valuesToSubmit);
            $.ajax({
                url: base_url("index.php/Linea/agregarLinea"),
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
    $("#form_linea")[0].reset();
    enivardatosmodalalmacen(this)
})
$(document).on("click",".btnnuevo",function(){
     $("#form_linea")[0].reset();
})
function borrarmodal()
{
    $(".modallineatitulo").html("Agregar Marca")
    $("#modalnombrelinea").val("")
    $("#modalsiglalinea").val("")
    $("#bguardar_linea").html("Guardar")
}
function enivardatosmodalalmacen(id)
{
    fila=$(id).parents("tr")
    cod=$(fila).attr("id");
    datos=$(fila).find("td")
    marca=$(datos[0]).html();
    sigla=$(datos[1]).html();
    $("#cod_linea").val(cod)
    $(".modallineatitulo").html("Editar Marca")
    $("#modalnombrelinea").val(marca)
    $("#modalsiglalinea").val(sigla)
    $("#bguardar_linea").html("Editar")
    $('#modallinea').modal('show');
    console.log(direccion)
}

