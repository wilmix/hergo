 <div class="box box-info">
      <div class="box-header with-border">
        <h3 class="box-title"><?php echo isset($titulo) ? $titulo :"" ?></h3>
      </div>
      <div class="box-body">
    <form class=" form-horizontal" action=" " method="post"  id="contact_form">
<!-- Tipo documento  -->
   
<div class="form-group"> 
  <label class="col-md-4 control-label">Tipo de Documento</label>
    <div class="col-md-4 selectContainer">
    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-equalizer"></i></span>
    <select name="tipo_doc" class="form-control selectpicker" >
      <option value=" " >Selecciona</option>
      <option>NIT</option>
      <option>CARNET DE IDENTIDAD</option>
      <option ></option>
      <option ></option>
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
  <input  name="carnet" placeholder="00000000" class="form-control"  type="number">
    </div>
  </div>
</div>



<!-- Nombre de Proovedor-->

<div class="form-group">
  <label class="col-md-4 control-label">Nombre de Proveedor</label>  
  <div class="col-md-4 inputGroupContainer">
  <div class="input-group">
  <span class="input-group-addon"><i class="glyphicon glyphicon-screenshot"></i></span>
  <input  name="nombre" placeholder="Nombre de Proveedor" class="form-control"  type="text">
    </div>
  </div>
</div>




<!-- Direccion-->
      
<div class="form-group">
  <label class="col-md-4 control-label">Direccion</label>  
    <div class="col-md-4 inputGroupContainer">
    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
  <input name="direccion" placeholder="Dirección de Proovedor" class="form-control" type="text">
    </div>
  </div>
</div>




<!-- Responsable -->

<div class="form-group">
  <label class="col-md-4 control-label">Responsable</label>  
  <div class="col-md-4 inputGroupContainer">
  <div class="input-group">
  <span class="input-group-addon"><i class="glyphicon glyphicon-tag"></i></span>
  <input  name="nombre_res" placeholder="Nombre de Responsable de Proovedor" class="form-control"  type="text">
    </div>
  </div>
</div>

<!-- Telefono -->
       
<div class="form-group">
  <label class="col-md-4 control-label">Telefono</label>  
    <div class="col-md-4 inputGroupContainer">
    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-earphone"></i></span>
  <input name="phone" placeholder="Telefono de Proovedor" class="form-control" type="number">
    </div>
  </div>
</div>

<!-- Telefono Fax-->
       
<div class="form-group">
  <label class="col-md-4 control-label">Telefono Fax</label>  
    <div class="col-md-4 inputGroupContainer">
    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-earphone"></i></span>
  <input name="phone" placeholder="Telefono Fax" class="form-control" type="number">
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
  <input name="website" placeholder="Sitio Web Proovedor" class="form-control" type="text">
    </div>
  </div>
</div>

<div class="form-group">
  <label class="col-md-4 control-label"></label>
  <div class="col-md-4">
    <button type="submit" class="btn btn-warning" >Send <span class="glyphicon glyphicon-send"></span></button>
  </div>
</div>

</fieldset>
</form>
</div>
    </div><!-- /.container -->
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


</script>