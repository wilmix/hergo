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
                                  max: 999999999999999999,
                                  message: 'Igrese un CI o NIT válido'
                              }
                          }
                      },
                  nombre_cliente: {
                      validators: {
                              stringLength: {
                              min: 1,
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
                              min: 1,
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
                                  min: 1,
                                  max: 999999999999999,
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
                    if (returndata=='true') {
                        $('#modalcliente').modal('hide');
                        resetForm('#form_clientes')
                        retornarTablaClientes()
                        swal(
                            'Cliente Registrado',
                            '',
                            'success'
                            )
                        console.log(returndata);
                    } else {
                        console.log(returndata);
                        client = JSON.parse(returndata)
                        console.log(client);
                        swal({
                            title: 'Atencion',
                            html: "El NIT <b>" + client.documento + "</b> ya se encuentra registrado a nombre de <b>" + client.nombreCliente + "</b> en fecha  <b>" + formato_fecha_corta(client.fecha) + "</b> registrado por <b>" + client.autor + "</b>.",
                            type: 'warning',
                        }
                        )
                    }

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
