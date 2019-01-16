$(document).ready(function () {
    $('#form_clientes').bootstrapValidator({
            // To use feedback icons, ensure that you use Bootstrap v3.1.0 or later
            feedbackIcons: {
                valid: 'glyphicon glyphicon-ok',
                invalid: 'glyphicon glyphicon-remove',
                validating: 'glyphicon glyphicon-refresh'
            },
            fields: {
                tipo_doc: {
                    validators: {
                        notEmpty: {
                            message: 'Selecciona NIT o CI'
                        }
                    }
                },
              carnet: {
                        validators: {
                          notEmpty: {
                            message: 'Campo obligatorio'
                        },
                            between: {
                                min: 1111,
                                max: 9999999999999999,
                                message: 'Igrese un CI o NIT válido'
                            }
                        }
                    },
                nombre_cliente: {
                    validators: {
                            stringLength: {
                            min: 2,
                            message: 'Ingrese nombre válido'
                        },
                            notEmpty: {
                            message: 'Campo obligatorio'
                        }
                    }
                },
                clientetipo: {
                    validators: {
                        notEmpty: {
                            message: 'Selecciona NIT o CI'
                        }
                    }
                },
              direccion: {
                    validators: {
                         stringLength: {
                            min: 5,
                            message: 'Ingrese dirección válida'
                        },
                    }
                },
                 email: {
                    validators: {
                            emailAddress: {
                            message: 'Ingrese un email válido'
                        }
                    }
                },
              phone: {
                        validators: {
                             between: {
                                min: 1111,
                                max: 99999999,
                                message: 'Igrese número de telefono valido'
                            }
                        }
                    },
            }
        })
        .on('success.form.bv', function (e) {
            e.preventDefault();
            //var valuesToSubmit = $("#form_clientes").serialize();  
            var formData = new FormData($('#form_clientes')[0]);
            console.log(formData)
            $.ajax({
                url: base_url("index.php/Clientes/agregarCliente"),
                type: 'POST',
                data: formData,
                cache: false,
                contentType: false,
                processData: false,
                success: function (returndata) {
                    $(".mensaje_ok").html(" Los datos se guardaron correctamente");
                    // $("#modal_ok").modal("show");
                    $('#contact-form-success').show().fadeOut(10000);
                    $('#modalcliente').modal('hide');
                },
                error : function (returndata) {
                    swal(
                        'Error',
                        'El número de documento ya se encuentra registrado en nuestra bases de datos',
                        'error'
                    )
                    //console.log(returndata);
                }, 
            });
        });
});
$(document).on("click", "#botonmodalcliente", function () {
    resetForm('#form_clientes')
    $(".modal-title").html("Agregar Cliente")
    $("#bguardar").html("Guardar")
})
$(document).on("click", ".botoncerrarmodal", function () {
    resetForm('#form_clientes')
})
