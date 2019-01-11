 <div class="box box-info">
      <div class="box-header with-border">
        <h3 class="box-title"><?php echo isset($titulo) ? $titulo :"" ?></h3>
      </div>
      <div class="box-body">
        <form class=" form-horizontal" action=" " method="post"  id="contact_form">

            <div class="form-group">
              <label class="col-md-4 control-label">Código</label>  
              <div class="col-md-4 inputGroupContainer">
              <div class="input-group">
              <span class="input-group-addon"><i class="glyphicon glyphicon-barcode"></i></span>
              <input  autofocus name="codigo" placeholder="Código de Articulo" class="form-control"  type="text" style="text-transform:uppercase; " onkeyup="javascript:this.value=this.value.toUpperCase();" >
                </div>
              </div>
            </div>
            <!-- Descripcion-->

            <div class="form-group">
              <label class="col-md-4 control-label">Descripcion</label>  
              <div class="col-md-4 inputGroupContainer">
              <div class="input-group">
              <span class="input-group-addon"><i class="glyphicon glyphicon-screenshot"></i></span>
              <input  name="descripcion" placeholder="Descripción de artículo" class="form-control"  type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
                </div>
              </div>
            </div>
            <!-- Unidad  -->
            <div class="form-group"> 
              <label class="col-md-4 control-label">Unidad</label>
                <div class="col-md-4 selectContainer">
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-equalizer"></i></span>
                <select name="unidad" class="form-control selectpicker" >
                  <option value=" " >Selecciona</option>
                  <option>PZA</option>
                  <option>MTR</option>
                  <option >RLL</option>
                  <option >CJA</option>
                </select>
              </div>
            </div>
            </div>
            <!-- Marca  -->          
            <div class="form-group"> 
              <label class="col-md-4 control-label">Marca</label>
                <div class="col-md-4 selectContainer">
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-th-large"></i></span>
                <select name="unidad" class="form-control selectpicker" >
                  <option value=" " >Selecciona</option>
                  <option>HANSA</option>
                  <option>INDURA</option>
                  <option >INFRA</option>
                  <option >3M</option>
                </select>
              </div>
            </div>
            </div>
            <!-- Linea  -->
               
            <div class="form-group"> 
              <label class="col-md-4 control-label">Linea</label>
                <div class="col-md-4 selectContainer">
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-th"></i></span>
                <select name="unidad" class="form-control selectpicker" >
                  <option value=" " >Selecciona</option>
                  <option>HANSA</option>
                  <option>INDURA</option>
                  <option >INFRA</option>
                  <option >3M</option>
                </select>
              </div>
            </div>
            </div>

            <div class="form-group">
              <label class="col-md-4 control-label">Precio</label>  
              <div class="col-md-4 inputGroupContainer">
              <div class="input-group">
              <span class="input-group-addon"><i class="glyphicon glyphicon-tag"></i></span>
              <input  name="precio" placeholder="W1003181" class="form-control"  type="text" >
                </div>
              </div>
            </div>


            <!-- Numero de parte ALFANUMERICO-->

            <div class="form-group">
              <label class="col-md-4 control-label">Número de Parte</label>  
              <div class="col-md-4 inputGroupContainer">
              <div class="input-group">
              <span class="input-group-addon"><i class="glyphicon glyphicon-tag"></i></span>
              <input  name="parte" placeholder="W1003181" class="form-control"  type="text" style="text-transform:uppercase;" onkeyup="javascript:this.value=this.value.toUpperCase();">
                </div>
              </div>
            </div>


            <!-- Posicion arancelaria 10 numeros-->

            <div class="form-group">
              <label class="col-md-4 control-label">Posicion Arancelaria</label>  
              <div class="col-md-4 inputGroupContainer">
              <div class="input-group">
              <span class="input-group-addon"><i class="glyphicon glyphicon-indent-left"></i></span>
              <input  name="posicion" placeholder="8424100000" class="form-control"  type="number">
                </div>
              </div>
            </div>


            <!-- Autorizacion-->
               
            <div class="form-group"> 
              <label class="col-md-4 control-label">Autorización</label>
                <div class="col-md-4 selectContainer">
                <div class="input-group">
                    <span class="input-group-addon"><i class="glyphicon glyphicon-font"></i></span>
                <select name="autor" class="form-control selectpicker" >
                  <option value=" " >Si corresponde</option>
                  <option>IBM</option>
                  <option>ROPA</option>
                  <option selected>NINGUNA</option>
                  <option ></option>
                </select>
              </div>
            </div>
            </div>


            <!-- Producto o servicio -->
             <div class="form-group">
                <label class="col-md-4 control-label">Producto o Servicio</label>
                  <div class="col-md-4">
                      <div class="radio">
                        <label><input type="radio" name="producto" value="1"/> Producto </label>
                        <label><input type="radio" name="producto" value="0" /> Servicio </label>
                      </div>
                  </div>
              </div>


            <!-- Imagen -->

             <div class="form-group">
                <label class="col-md-4 control-label">Imagen de artículo</label>
                  <div class="col-md-4">
                <input type="file" id="exampleInputFile" name="imagen">
                <p class="help-block">Seleccione imagen para el articulo menor a 1mb.</p>
              </div>
              </div>



            <!-- Uso -->
            <div class="form-group">
              <label class="col-md-4 control-label">En Uso</label>
              <div class="col-md-4">
                <div class="radio">
                  <label><input type="radio" name="enuso" value="1" checked/> Si </label>
                  <label><input type="radio" name="enuso" value="0" /> No </label>
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
          codigo: {
                validators: {
                        stringLength: {
                        min: 6,
                        max: 6,
                        message: 'Ingrese cógigo válido'
                    },
                        notEmpty: {
                        message: 'Campo obligatorio'
                    }
                }
            },
           descripcion: {
                validators: {
                        stringLength: {
                        min: 5,
                        message: 'Ingrese descrición válida'
                        
                    },
                   notEmpty: {
                        message: 'Campo obligatorio'
                    }
                        
                }
            },
          unidad: {
                validators: {
                    notEmpty: {
                        message: 'Campo obligatorio'
                    }
                }
            },
          autor: {
                validators: {
                  notEmpty: {
                        message: 'Campo obligatorio'
                    }
                    
                }
            },
          posicion: {
                    validators: {
                     
                        between: {
                            min: 1,
                            max: 9999999999,
                            message: 'Igrese Posicion Arancelaria Valida'
                        }
                    }
                },
            
          parte: {
                validators: {
                     stringLength: {
                        min: 3,
                    },
                }
            },
         
            imagen: {
                validators: {
                     file: {
                        extension: 'jpeg,jpg,png',
                        type: 'image/jpeg,image/png',
                        maxSize: 2097152,   // 2048 * 1024
                        message: 'Archivo no válido'
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
</script>



