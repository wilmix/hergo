<body>

        <!-- Top content -->
        <div class="top-content">
        	
            <div class=""><!-- inner-bg-->
                <div class="container">
                    <div class="row">
                        <div class="col-sm-8 col-sm-offset-2 text">
                            <h1><strong>Sistema de Inventarios</strong> Hergo Ltda.</h1>
                            <div class="description hidden-xs">
                            	<p>
	                            	Sistema Inventarios Web On-line<br>
	                            	Inteligente Rápida Eficaz Responsiva
                                </p>
                            </div>
                            <?php 
                            if(isset($message)){ ?>
                            <div class="alert alert-warning alert-dismissible">
                                <button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
                                <h4><i class="icon fa fa-info"></i> Atencion!</h4>
                                <?php echo $message; ?>
                            </div>
                            <?php } ?>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-sm-6 col-sm-offset-3 form-box">
                        	<div class="form-top">
                        		<div class="form-top-left">
                        			<h3>Entrar al Sistema</h3>
                            		<p>Ingresa tu usuario y contraseña:</p>

                        		</div>
                        		<div class="form-top-right">
                        			<i class="fa fa-lock"></i>
                        		</div>
                            </div>
                            <div class="form-bottom">
			                   <form role="form" action="<?php echo base_url('index.php/auth/login') ?>" method="post" class="login-form">
                               <!--<?php echo form_open("auth/login", "role='form'"); ?>-->
			                    	<div class="form-group">
			                    		<label class="sr-only" for="form-username">Usuario</label>
			                        	
                                        <?php echo form_input($identity, '', array('class' => 'form-username form-control','placeholder' => "Usuario...")); ?>
			                        </div>
			                        <div class="form-group">
			                        	<label class="sr-only" for="form-password">Contraseña</label>
			                        	
                                        <?php echo form_input($password, '', array('class' => 'form-control', 'placeholder' => "Contraseña...")); ?>
			                        </div>
                                    <?php 
                                    $data = array(
                                            'type'          => 'submit',
                                            'content'       => 'Ingresar!',
                                            'class'         => 'btn'
                                    );
                                    ?>
                                    <?php echo form_button($data); ?>
			                       
                                <!--<?php echo form_close(); ?>-->
			                    </form>
		                    </div>
                        </div>
                    </div>
                   
                    
                </div>
            </div>
            
        </div>
</body>

