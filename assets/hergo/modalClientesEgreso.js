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
                            max: 999999999,
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
                }
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
function mostrarModal(fila) {
    console.log(fila)
    $("#id_cliente").val(fila.idCliente)
    $(".modallineatitulo").html("Editar Cliente")
    asignarselect(fila.documentoTipo, "#tipo_doc")
    $("#carnet").val(fila.documento)
    $("#nombre_cliente").val(fila.nombreCliente)
    asignarselect(fila.clientetipo, "#clientetipo")
    $("#direccion").val(fila.direccion)
    $("#phone").val(fila.telefono)
    $("#fax").val(fila.fax)
    $("#email").val(fila.email)
    $("#website").val(fila.web)
    $(".bguardar").html("Editar")
    $('#modalcliente').modal('show');
}

