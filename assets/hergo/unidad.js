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
                        min: 1,
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
                        min: 1,
                        max: 100,
                        message: ''
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
            let valuesToSubmit = $("#form_unidad").serialize();
            $.ajax({
                url: base_url("index.php/Unidad/agregarUnidad"),
                data: valuesToSubmit,              
                type: 'POST',
            })
            .done(function( data, textStatus, jqXHR ) {
                resetForm('#form_unidad')
                swal(
                    'Unidad guardada',
                    '',
                    'success'
                    )
                $('#modalunidad').modal('hide')
                document.location.href=""
            })
            .fail(function( jqXHR, textStatus, errorThrown ) {
                swal(
                    'Error',
                    'La unidad ya se encuentra registrado en nuestra bases de datos',
                    'error'
                )
            });
        });
});/**FIN READY**/
/********MODAL ALMACEN EDITAR**********/
$(document).on("click",".botoneditar",function(){
    resetForm('#form_unidad')
    enivardatosmodalalmacen(this)
})
$(document).on("click",".btnnuevo",function(){
    resetForm('#form_unidad')
})
function enivardatosmodalalmacen(data)
{
    fila=$(data).parents("tr")
    id=$(fila).attr("id");
    datos=$(fila).find("td")
    uni=$(datos[0]).html();
    sigla=$(datos[1]).html();
    console.log(fila)
    console.log(id);
    console.log(datos);
    $("#cod_unidad").val(id)
    $(".modalunidadtitulo").html("Editar Unidad")
    $("#modalnombreunidad").val(uni)
    $("#modalsiglaunidad").val(sigla)
    $("#bguardar_unidad").html("Editar")
    $('#modalunidad').modal('show');
}

