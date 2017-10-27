
<section class="content-header">
      <h1>
        Usuarios
        <small>Desactivar usuario</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i>Inicio</a></li>
        <li><a href="#">Usuarios</a></li>
        <li class="active">Desactivar usuario</li>
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
                  <h3 class="box-title">Desactivar usuario</h3>
                </div>
                <!-- /.box-header -->
                <!-- form start -->
                <form role="form" action="<?php echo current_url() ?>" method="post" class="form-horizontal">
                
                  <div class="box-body">
                    <div class="col-sm-6">
                       <p>
					  	<?php echo lang('deactivate_confirm_y_label', 'confirm');?>
					    <input type="radio" name="confirm" value="yes" checked="checked" />
					    <?php echo lang('deactivate_confirm_n_label', 'confirm');?>
					    <input type="radio" name="confirm" value="no" />
					  </p>

					  <?php echo form_hidden($csrf); ?>
					  <?php echo form_hidden(array('id'=>$user->id)); ?>

					 
                    </div>
                    
                    
                  </div>
                  <!-- /.box-body -->
                  <div class="box-footer">
                	 <a href="<?php echo base_url('index.php/auth/usuarios') ?>" class="btn btn-default pull-left" role="button">Cancelar</a>
                    <button type="submit" class="btn btn-info pull-right">Desactivar</button>
                  </div>
                  <!-- /.box-footer -->
                </form>
            </div>
        </div>
    </div>
</section>
</div> <!-- FIN content-wrapper --> 





<h1><?php echo lang('deactivate_heading');?></h1>
<p><?php echo sprintf(lang('deactivate_subheading'), $user->username);?></p>

<?php echo form_open("auth/deactivate/".$user->id);?>

  <p>
  	<?php echo lang('deactivate_confirm_y_label', 'confirm');?>
    <input type="radio" name="confirm" value="yes" checked="checked" />
    <?php echo lang('deactivate_confirm_n_label', 'confirm');?>
    <input type="radio" name="confirm" value="no" />
  </p>

  <?php echo form_hidden($csrf); ?>
  <?php echo form_hidden(array('id'=>$user->id)); ?>

  <p><?php echo form_submit('submit', lang('deactivate_submit_btn'));?></p>

<?php echo form_close();?>