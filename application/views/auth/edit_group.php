
<section class="content-header">
      <h1>
        Usuarios
        <small>Editar Grupo</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i>Inicio</a></li>
        <li><a href="#">Usuarios</a></li>
        <li class="active">Editar Grupo</li>
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
                  <h3 class="box-title">Editar datos del grupo</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form role="form" action="<?php echo current_url() ?>" method="post" class="form-horizontal">
                
                  <div class="box-body">
                    <div class="col-sm-6">
                        <div class="form-group">
                          <label for="inputEmail3" class="col-sm-2 control-label">Nombre de Grupo</label>
                          <div class="col-sm-9">

                            <?php 
                              echo form_input($group_name);
                           ?>
                          </div>
                        </div>
                        
                        <div class="form-group">
                          <label for="inputPassword3" class="col-sm-2 control-label">Descripcion</label>

                          <div class="col-sm-9">
                            <?php 
                              echo form_input($group_description);
                           ?>
                          </div>
                        </div>
                    </div>
                    
                    
                  </div>
                  <!-- /.box-body -->
                  <div class="box-footer">
                	 <a href="<?php echo base_url('index.php/auth/usuarios') ?>" class="btn btn-default pull-left" role="button">Cancelar</a>
                    <button type="submit" class="btn btn-info pull-right">Crear grupo</button>
                  </div>
                  <!-- /.box-footer -->
                </form>
            </div>
        </div>
    </div>
</section>
</div> <!-- FIN content-wrapper --> 
