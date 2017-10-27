
<section class="content-header">
      <h1>
        Usuarios
        <small>Esditar Usuario</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i>Inicio</a></li>
        <li><a href="#">Usuarios</a></li>
        <li class="active">Editar Usuario</li>
      </ol>
</section>
<?php 
if(isset($message)){ ?>
<div class="alert alert-warning alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h4><i class="icon fa fa-info"></i> Atencion!</h4>
    <?php echo $message; ?>
</div>
<?php } ?>
<section class="content">
    <div class="row">
        <div class="col-xs-12">
            <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title">Editar datos de usuario</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form role="form" action="<?php echo current_url()?>" method="post" class="form-horizontal"  enctype="multipart/form-data">
                
                  <div class="box-body">
                    <div class="col-sm-6">
                        <div class="form-group">
                          <label for="first_name" class="col-sm-2 control-label">Nombre</label>

                          <div class="col-sm-10">
                          <?php 
                              
                              echo form_input($first_name);
                           ?>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="idapellido" class="col-sm-2 control-label">Apellido</label>
                          <div class="col-sm-10"> 
                            <?php 
                              
                              echo form_input($last_name);
                           ?>
                          </div>
                        </div>
                        
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Compañia</label>
                          <div class="col-sm-10">
                           <?php 
                              echo form_input($company);
                           ?>
                            
                          </div>
                        </div>


                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                          <label for="inputPassword3" class="col-sm-2 control-label">Telefono</label>

                          <div class="col-sm-10">
                            <?php 
                              echo form_input($phone);
                           ?>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputPassword3" class="col-sm-2 control-label">Contraseña</label>

                          <div class="col-sm-10">
                            <?php 
                              echo form_input($password);
                           ?>
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputPassword3" class="col-sm-2 control-label">Confirmar contraseña</label>

                          <div class="col-sm-10">
                            <?php 
                              echo form_input($password_confirm);
                           ?>
                          </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <hr>
                      <h3 class="box-title">Imagen</h3>
                      <input id="input-1" name="imagenes[]" type="file" class="file-loading" accept="image/*">
                    <hr>
                    <div class="row">

                     <div class="col-sm-6">
                     
                        <?php if ($this->ion_auth->is_admin()): ?>
                         

                        <h3 class="box-title">Miembro de grupos</h3>
                        <div class="checkbox">
                        <?php foreach ($groups as $group):?>
                            <label class="checkbox">
                            <?php
                                $gID=$group['id'];
                                $checked = null;
                                $item = null;
                                foreach($currentGroups as $grp) {
                                    if ($gID == $grp->id) {
                                        $checked= ' checked="checked"';
                                    break;
                                    }
                                }
                            ?>
                            <input type="checkbox" name="groups[]" value="<?php echo $group['id'];?>"<?php echo $checked;?>>
                            <?php echo htmlspecialchars($group['name'],ENT_QUOTES,'UTF-8');?>
                            </label>
                        <?php endforeach?>
                        </div>
                        <?php endif ?>

                        <?php echo form_hidden('id', $user->id);?>
                        <?php echo form_hidden($csrf); ?>
                        <div style="clear:both"></div>
                     </div>
                  
                    
                    </div>
                    
                    
                  </div>

                  <!-- /.box-body -->
                  <div class="box-footer">
                     <a href="<?php echo base_url('index.php/auth/usuarios') ?>" class="btn btn-default pull-left" role="button">Cancelar</a>
                    <button type="submit" class="btn btn-info pull-right">Guardar datos</button>
                  </div>
                  <!-- /.box-footer -->
                </form>
            </div>
        </div>
    </div>
</section>
<script>
    $("#input-1").fileinput({
        language: "es",
        showUpload: false,
        previewFileType: "image",
        maxFileSize: 1024,
       
    });

</script>
</div> <!-- FIN content-wrapper --> 







