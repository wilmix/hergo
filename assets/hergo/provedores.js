$(document).ready(function() {
    $('#form_provedor').bootstrapValidator({
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
                        message: 'Carnet o NIT campo obligatorio'
                    },
                        between: {
                            min: 1111,
                            max: 99999999,
                            message: 'Igrese un CI o NIT valido'
                        }
                    }
                },
            nombre: {
                validators: {
                        stringLength: {
                        min: 2,
                    },
                        notEmpty: {
                        message: 'Campo obligatorio'
                    }
                }
            },
           nombre_res: {
                validators: {
                        stringLength: {
                        min: 2,
                    },
                        
                }
            },
          direccion: {
                validators: {
                     stringLength: {
                        min: 5,
                    },
                }
            },
             email: {
                validators: {
                        emailAddress: {
                        message: 'Please supply a valid email address'
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
          website: {
                validators: {
                        stringLength: {
                        min: 2,
                    },

                }
            },
           }
        })
        .on('success.form.bv', function(e) {
            $('#success_message').slideDown({ opacity: "show" }, "slow") // Do something ...
                $('#contact_form').data('bootstrapValidator').resetForm();

            // Prevent form submission
            e.preventDefault();

            // Get the form instance
            var $form = $(e.target);

            // Get the BootstrapValidator instance
            var bv = $form.data('bootstrapValidator');

            // Use Ajax to submit form data
            $.post($form.attr('action'), $form.serialize(), function(result) {
                console.log(result);
            }, 'json');
        });
});
