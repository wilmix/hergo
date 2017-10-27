  <div class="box box-info">
      <div class="box-header with-border">
        <h3 class="box-title"><?php echo isset($titulo) ? $titulo :"" ?></h3>
      </div>
      <div class="box-body">

      <form class="form-horizontal"  method="post"  id="contact_form">
        <div class="form-group">
          <label class="col-md-4 control-label" for="inputEmail3">Nombre de Almacen</label>  
          <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
            <span class="input-group-addon"><i class="glyphicon glyphicon-barcode"></i></span>
            <input  autofocus name="almacen" placeholder="Nombre de Almacen" class="form-control"  type="text" style="text-transform:uppercase; " onkeyup="javascript:this.value=this.value.toUpperCase();">
            </div>
          </div>
          <div class="clearfix"></div>
        </div>
        <!-- Direccion de almacen-->
        <div class="form-group">
          <label class="col-md-4 control-label">Dirección de Almacen</label>  
          <div class="col-md-4 inputGroupContainer">
            <div class="input-group">
              <span class="input-group-addon"><i class="glyphicon glyphicon-screenshot"></i></span>
              <input  name="direccion" placeholder="Dirección de Almacen" class="form-control"  type="text"  style="text-transform:uppercase; " onkeyup="javascript:this.value=this.value.toUpperCase();">
            </div>
          </div>
          <div class="clearfix"></div>
        </div>
        <!-- Ciudad  -->
        <div class="form-group"> 
          <label class="col-md-4 control-label">Ciudad</label>
            <div class="col-md-4 selectContainer">
              <div class="input-group">
                  <span class="input-group-addon"><i class="glyphicon glyphicon-pushpin"></i></span>
              <select name="ciudad" class="form-control selectpicker" >
                <option value=" " >Selecciona</option>
                <option>La Paz</option>
                <option>Potosi</option>
                <option >Santa Cruz</option>
                <option >Tarija</option>
              </select>
            </div>
          </div>
          <div class="clearfix"></div>
        </div>
        <!-- Uso -->
         <div class="form-group">
            <label class="col-md-4 control-label">En Uso</label>
            <div class="col-md-4">
                <div class="radio">
                    <label><input type="radio" name="hosting" value="yes" checked/> Si </label>
                    <label><input type="radio" name="hosting" value="no" /> No </label>
                </div>
            </div>
            <div class="clearfix"></div>
          </div>
        <!-- Button -->
        <div class="form-group">
          <label class="col-md-4 control-label"></label>
          <div class="col-md-4">
            <button type="submit" class="btn btn-warning" >Send <span class="glyphicon glyphicon-send"></span></button>
          </div>
          <div class="clearfix"></div>
        </div>       
      </form>
    </div>
  </div><!-- /.container -->
</div>
                <!-- /.box-header -->
                <!-- form start -->
</div>
<script>
  $(document).ready(function() {
    $('#contacts_form').bootstrapValidator({
        // To use feedback icons, ensure that you use Bootstrap v3.1.0 or later
             feedbackIcons: {
            valid: 'glyphicon glyphicon-ok',
            invalid: 'glyphicon glyphicon-remove',
            validating: 'glyphicon glyphicon-refresh'
        },
        fields: {
          
           almacen: {
                validators: {
                        stringLength: {
                        min: 5,
                        message: 'Ingrese nombre de almacen válido'
                        
                    },
                   notEmpty: {
                        message: 'Campo obligatorio'
                    }
                        
                }
            },
          direccion: {
                validators: {
                }
            },
            
          ciudad: {
                validators: {
                     stringLength: {
                        min: 3,
                        message: 'Selecciona ciudad'
                    },
                                    notEmpty: {
                        message: 'Campo obligatorio'
                    }
                }
            },
         
           
           }
        })
        .on('success.form.bv', function(e) {
          alert("sdsds")
            // Prevent form submission
           // e.preventDefault();

            // Get the form instance
            var $form = $(e.target);

            // Get the BootstrapValidator instance
            var bv = $form.data('bootstrapValidator');

            // Use Ajax to submit form data
            $.post($form.attr('action'), $form.serialize(), function(result) {
                // ... Process the result ...
            }, 'json');
        });
});


//function validarn(e) { // 1
//    tecla = (document.all) ? e.keyCode : e.which; // 2
//    if (tecla==8) return true; // 3
//   if (tecla==9) return true; // 3
//   if (tecla==11) return true; // 3
//    patron = /[A-Za-zñÑ'áéíóúÁÉÍÓÚàèìòùÀÈÌÒÙâêîôûÂÊÎÔÛÑñäëïöüÄËÏÖÜ\s\t]/; // 4
// 
//    te = String.fromCharCode(tecla); // 5
//    return patron.test(te); // 6
//} 


</script>