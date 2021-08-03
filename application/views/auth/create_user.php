
<?php 
if(isset($message)){ ?>
<div class="alert alert-warning alert-dismissible">
    <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <h4><i class="icon fa fa-info"></i> Atencion!</h4>
    <?php echo $message; ?>
</div>
<?php } ?>

    <div class="row">
        <div class="col-xs-12">
            <div class="box box-info">
                <div class="box-header with-border">
                  <h3 class="box-title">Agregar nuevo usuario</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form role="form" action="<?php echo base_url('index.php/auth/create_user') ?>" method="post" class="form-horizontal">
                
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
                        <?php
                            
                              if($identity_column!=='email') {
                                ?>
                        <div class="form-group">
                          <label for="idcorreo" class="col-sm-2 control-label">Correo</label>

                          <div class="col-sm-10">                       
                            <?php 
                                
                                  echo '<p>';
                                  echo lang('create_user_identity_label', 'identity');
                                  echo '<br />';
                                  echo form_error('identity');
                                  echo form_input($identity);
                                  echo '</p>';
                              ?>
                          </div>
                        </div>
                        <?php } ?>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Cargo</label>
                          <div class="col-sm-10">
                           <?php 
                              echo form_input($company);
                           ?>
                            
                          </div>
                        </div>
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Almacen</label>
                          <div class="col-sm-10">
                           <?php 
                              echo form_input($almacen);
                           ?>
                            
                          </div>
                        </div>


                    </div>
                    <div class="col-sm-6">
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Email</label>
                          <div class="col-sm-10">

                            <?php 
                              echo form_input($email);
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
                    
                    
                  </div>
                  <!-- /.box-body -->
                  <div class="box-footer">
                    <a href="<?php echo base_url('index.php/auth/usuarios') ?>" class="btn btn-default pull-left" role="button">Cancelar</a>
                    
                    <button type="submit" class="btn btn-info pull-right">Crear usuario</button>
                  </div>
                  <!-- /.box-footer -->
                </form>
            </div>
        </div>
    </div>


