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
                unidadSiat: {
                    validators: {
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
                url: base_url("index.php/Unidad/addOrUpdate"),
                data: valuesToSubmit,              
                type: 'POST',
            })
            .done(function( data, textStatus, jqXHR ) {
                console.log(data);
                if (data == 'true') {
                    resetForm('#form_unidad')
                    swal(
                        'Unidad guardada',
                        '',
                        'success'
                        )
                    $('#modalunidad').modal('hide')
                    document.location.href=""
                } else {
                    swal(
                        'Error',
                        'No se pudo guardar la unidad nuestra bases de datos ' + data,
                        'error'
                    )
                }
            })
            .fail(function( jqXHR, textStatus, errorThrown, ) {
                swal(
                    'Error',
                    'No se pudo guardar la unidad nuestra bases de datos ' + errorThrown,
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
    siat_codigo=$(datos[2]).html();
    siat_unidadMedida=$(datos[3]).html();

    $("#cod_unidad").val(id)
    $(".modalunidadtitulo").html("Editar Unidad")
    $("#modalnombreunidad").val(uni)
    $("#modalsiglaunidad").val(sigla)
    $("#siat_codigo").val(siat_codigo)
    $("#siat_unidadMedida").val(siat_codigo)
    $("#bguardar_unidad").html("Editar")
    $('#modalunidad').modal('show');
}

