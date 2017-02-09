 <div class="box box-info">
      <div class="box-header with-border">
        <h3 class="box-title"><?php echo isset($titulo) ? $titulo :"" ?></h3>
      </div>
      <div class="box-body">
        <form class="form-horizontal" action=" " method="post"  id="contact_form">
       
          <div class="form-group"> 
            <label class="col-md-4 control-label">Tipo de Documento</label>
              <div class="col-md-4 selectContainer">
              <div class="input-group">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-equalizer"></i></span>
              <select name="state" class="form-control selectpicker" >
                <option value=" " >Selecciona</option>
                <option>NIT</option>
                <option>CARNET DE IDENTIDAD</option>
              </select>
            </div>
          </div>
          </div>



          <!-- Documento-->

          <div class="form-group">
            <label class="col-md-4 control-label">N° Documento</label>  
            <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-barcode"></i></span>
            <input  name="carnet" placeholder="00000000" class="form-control"  type="text">
              </div>
            </div>
          </div>



          <!-- Nombre de Cliente-->

          <div class="form-group">
            <label class="col-md-4 control-label">Nombre de Cliente</label>  
            <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
            <input  name="first_name" placeholder="Nombre o Razon Social" class="form-control"  type="text">
              </div>
            </div>
          </div>




          <!-- Direccion-->
                
          <div class="form-group">
            <label class="col-md-4 control-label">Direccion</label>  
              <div class="col-md-4 inputGroupContainer">
              <div class="input-group">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
            <input name="address" placeholder="Dirección de Cliente" class="form-control" type="text">
              </div>
            </div>
          </div>




          <!-- Telefono -->
                 
          <div class="form-group">
            <label class="col-md-4 control-label">Telefono</label>  
              <div class="col-md-4 inputGroupContainer">
              <div class="input-group">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-earphone"></i></span>
            <input name="phone" placeholder="Telefono de Cliente" class="form-control" type="text">
              </div>
            </div>
          </div>


          <!-- Email-->

          <div class="form-group">
            <label class="col-md-4 control-label">E-Mail</label>  
              <div class="col-md-4 inputGroupContainer">
              <div class="input-group">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-envelope"></i></span>
            <input name="email" placeholder="Dirección Email" class="form-control"  type="text">
              </div>
            </div>
          </div>

          <!-- web-->
          <div class="form-group">
            <label class="col-md-4 control-label">Sitio WEB</label>  
             <div class="col-md-4 inputGroupContainer">
              <div class="input-group">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-globe"></i></span>
            <input name="website" placeholder="Sitio Web Cliente" class="form-control" type="text">
              </div>
            </div>
          </div>




          <!-- Button -->
          <div class="form-group">
            <label class="col-md-4 control-label"></label>
            <div class="col-md-4">
              <button type="submit" class="btn btn-warning" >Send <span class="glyphicon glyphicon-send"></span></button>
            </div>
          </div>

          </fieldset>
      </form>
  </div>
                <!-- /.box-header -->
                <!-- form start -->
</div>
<script>
  $(document).ready(function() {
    $('#contact_form').bootstrapValidator({
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
          
          website: {
                validators: {
                    uri: {
                        message: 'The website address is not valid'
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

//style="text-transform:lowercase;" onkeyup="javascript:this.value=this.value.toLowerCase();
  
</script>
