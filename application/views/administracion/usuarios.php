 <div class="box box-info">
      <div class="box-header with-border">
        <h3 class="box-title"><?php echo isset($titulo) ? $titulo :"" ?></h3>
      </div>
      <div class="box-body">

    <form class=" form-horizontal" action=" " method="post"  id="contact_form">


<!-- Carnet-->

<div class="form-group">
  <label class="col-md-4 control-label">N° Carnet</label>  
  <div class="col-md-4 inputGroupContainer">
  <div class="input-group">
  <span class="input-group-addon"><i class="glyphicon glyphicon-barcode"></i></span>
  <input  name="carnet" placeholder="N° Carnet" class="form-control"  type="number">
    </div>
  </div>
</div>


<!-- Expedido  -->
   
<div class="form-group"> 
  <label class="col-md-4 control-label">Expedido</label>
    <div class="col-md-4 selectContainer">
    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-pushpin"></i></span>
    <select name="exp" class="form-control" >
      <option value=" " >Selecciona</option>
      <option>La Paz</option>
      <option>Potosi</option>
      <option >Santa Cruz</option>
      <option >Tarija</option>
    </select>
  </div>
</div>
</div>

<!-- Apellido Paterno-->

<div class="form-group">
  <label class="col-md-4 control-label">Apellido Paterno</label>  
  <div class="col-md-4 inputGroupContainer">
  <div class="input-group">
  <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
  <input  name="paterno" placeholder="Apellido Paterno" class="form-control"  type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
    </div>
  </div>
</div>

<!-- Apellido Materno-->

<div class="form-group">
  <label class="col-md-4 control-label" >Apellido Materno</label> 
    <div class="col-md-4 inputGroupContainer">
    <div class="input-group">
  <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
  <input name="materno" placeholder="Apellido Materno" class="form-control"  type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
    </div>
  </div>
</div>

<!-- Apellido Casada-->

<div class="form-group">
  <label class="col-md-4 control-label" >Apellido Casada</label> 
    <div class="col-md-4 inputGroupContainer">
    <div class="input-group">
  <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
  <input name="casada" placeholder="Apellido Casada" class="form-control"  type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
    </div>
  </div>
</div>

<!-- Nombres-->

<div class="form-group">
  <label class="col-md-4 control-label" >Nombres</label> 
    <div class="col-md-4 inputGroupContainer">
    <div class="input-group">
  <span class="input-group-addon"><i class="glyphicon glyphicon-user"></i></span>
  <input name="nombre" placeholder="Nombres" class="form-control"  type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
    </div>
  </div>
</div>

<!-- Sexo -->
 <div class="form-group">
                        <label class="col-md-4 control-label">Sexo</label>
                        <div class="col-md-4">
                            <div class="radio">
                                <label>
                                    <input type="radio" name="hosting" value="yes" /> Hombre
                                </label>
 
                                <label>
                                    <input type="radio" name="hosting" value="no" /> Mujer
                                </label>
                            </div>
                        </div>
                    </div>


<!-- Fecha de Nacimiento-->
       <div class="form-group">
  <label class="col-md-4 control-label">Fecha de Nacimiento</label>  
    <div class="col-md-4 inputGroupContainer">
    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
  <input name="fecha" placeholder="Fecha de Nacimiento" class="form-control"  type="date">
    </div>
  </div>
</div>


<!-- Direccion-->
      
<div class="form-group">
  <label class="col-md-4 control-label">Direccion</label>  
    <div class="col-md-4 inputGroupContainer">
    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
  <input name="direccion" placeholder="Dirección" class="form-control" type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
    </div>
  </div>
</div>



<!-- Telefono Celular-->
       
<div class="form-group">
  <label class="col-md-4 control-label">Telefono Celular</label>  
    <div class="col-md-4 inputGroupContainer">
    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-earphone"></i></span>
  <input name="phone" placeholder="70572005" class="form-control" type="text">
    </div>
  </div>
</div>

<!-- Telefono Referencia-->
       
<div class="form-group">
  <label class="col-md-4 control-label">Telefono Referencia</label>  
    <div class="col-md-4 inputGroupContainer">
    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-earphone"></i></span>
  <input name="phone" placeholder="2232145" class="form-control" type="text">
    </div>
  </div>
</div>





<legend>Informacion Trabajo</legend>






<!-- Area -->

<div class="form-group"> 
  <label class="col-md-4 control-label">Area</label>
    <div class="col-md-4 selectContainer">
    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-text-background"></i></span>
    <select name="area" class="form-control" >
      <option value=" " >Selecciona el Area de trabajo</option>
      <option>Ventas</option>
      <option>Contabilidad</option>
      <option >Administracion</option>
      <option >Almacen</option>
    </select>
  </div>
</div>
</div>


<!-- Cargo -->

<div class="form-group"> 
  <label class="col-md-4 control-label">Cargo</label>
    <div class="col-md-4 selectContainer">
    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-object-align-vertical"></i></span>
    <select name="cargo" class="form-control" >
      <option value=" " >Selecciona su Cargo</option>
      <option>Ejecutivo de ventas</option>
      <option>Auxiliar de ventas</option>
      <option >Contador</option>
      <option >Auxiliar Contable</option>
    </select>
  </div>
</div>
</div>

<!-- Fecha de Ingreso a la empresa-->
       <div class="form-group">
  <label class="col-md-4 control-label">Fecha de Ingreso</label>  
    <div class="col-md-4 inputGroupContainer">
    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-calendar"></i></span>
  <input name="fecha" placeholder="Fecha de Ingreso" class="form-control"  type="date">
    </div>
  </div>
</div>

<!-- Telefono Coorporativo-->
       
<div class="form-group">
  <label class="col-md-4 control-label">Telefono Coorporativo</label>  
    <div class="col-md-4 inputGroupContainer">
    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-earphone"></i></span>
  <input name="phone" placeholder="78255658" class="form-control" type="text">
    </div>
  </div>
</div>

<!-- Ciudad -->
   
<div class="form-group"> 
  <label class="col-md-4 control-label">Ciudad</label>
    <div class="col-md-4 selectContainer">
    <div class="input-group">
        <span class="input-group-addon"><i class="glyphicon glyphicon-home"></i></span>
    <select name="exp" class="form-control" >
      <option value=" " >Selecciona la ciudad</option>
      <option>La Paz</option>
      <option>Potosi</option>
      <option >Santa Cruz</option>
      <option >Tarija</option>
    </select>
  </div>
</div>
  
  <!-- Button -->
<div class="form-group">
  <label class="col-md-4 control-label"></label>
  <div class="col-md-4">
    <button type="submit" class="btn btn-warning" >Send <span class="glyphicon glyphicon-send"></span></button>
  </div>
</div>
  
  
</div>
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
                        message: 'Carnet campo obligatorio'
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
          paterno: {
                validators: {
                        stringLength: {
                        min: 2,
                    },
                        notEmpty: {
                        message: 'Campo obligatorio'
                    }
                }
            },
          materno: {
                validators: {
                        stringLength: {
                        min: 2,
                    },

                }
            },
           casada: {
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
          fecha: {
                    validators: {
                        notEmpty: {
                            message: 'Campo obligatorio'
                        },
                        date: {
                            format: 'DD/MM/YYYY',
                            message: 'The date is not a valid'
                        }
                    }
                },
          exp: {
                validators: {
                    notEmpty: {
                        message: 'Selecciona Ciudad'
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
          cargo: {
                validators: {
                    notEmpty: {
                        message: 'Selecciona Cargo'
                    }
                }
            },
         area: {
               validators: {
               notEmpty: {
                      message: 'Selecciona Area de Trabajo'
                 }
               }
          },
    
            //    }
        //    },
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