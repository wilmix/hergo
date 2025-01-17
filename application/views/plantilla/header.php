
  <body class="<?= $skin ?> sidebar-mini fixed">

    <input type="hidden" id="baseurl" value="<?php echo base_url() ?>">
    <input type="hidden" id="user_id_actual" value="<?php echo $user_id_actual ?>">
    <input type="hidden" id="base_url_siat" value="<?php echo $this->config->item('base_url_siat') ?>">
    <input type="hidden"  id="gestionActual" value="<?php echo $gestionActual->gestionActual ?>">

    <div class="wrapper">

      <!-- Main Header -->
      <header class="main-header">

        <!-- Logo -->
        <a href="<?php echo base_url("index.php/principal") ?>" class="logo">
          <!-- mini logo for sidebar mini 50x50 pixels -->
          <span class="logo-mini">
            <img src="<?php echo base_url("assets/dist/img/logo1.png") ?>">
          </span>
          <!-- logo for regular state and mobile devices -->
          <span class="logo-lg"><img src="<?php echo base_url("assets/dist/img/logo1.png") ?>">Hergo</span>
        </a>

        <!-- Header Navbar -->
        <nav class="navbar navbar-static-top fh-fixedHeader" role="navigation">
          <!-- Sidebar toggle button-->
          <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
            <span class="sr-only">Toggle navigation</span>
          </a>
          <!-- Navbar Right Menu -->
          <div class="navbar-custom-menu">
            <ul class="nav navbar-nav">
            <!-- database  -->
            <li>
              <a href="" target="_blank" class="dropdown-toggle" aria-expanded="true"> 
                <i  <?php echo $datosDataBase == '' ?   '' :  'class="fa fa-database"'  ?>></i>
                <?php echo $datosDataBase  ?>
              </a>
            </li>
            <!-- email  -->
            <li class="dropdown messages-menu">
              <a href="https://webmail.hergo.com.bo/" target="_blank" class="dropdown-toggle" aria-expanded="true">
                <i class="fa fa-envelope-o"></i>
              </a>
            </li>
            <!-- Tipo de cambio -->
              <li class="dropdown notifications-menu">
                <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true">
                  <i class="fas fa-dollar-sign"></i>
                  <span class="label label-info"></span>
                </a>
                <ul class="dropdown-menu">
                  <li class="header">El tipo de cambio del sistema es 6.96</li>
                  <li>
                    <a href="<?php echo base_url("index.php/configuracion/tipoCambio") ?>">
                      <i class="fa fa-refresh text-aqua"></i> Cambiar tipo de cambio
                    </a>
                  </li>
                </ul>
              </li>
              
              <!-- User Account Menu -->
              <li class="dropdown user user-menu">
                <!-- Menu Toggle Button -->
                <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                  <!-- The user image in the navbar -->
                  <img src="<?php echo $foto ?>" class="user-image" alt="User Image">
                  <!-- hidden-xs hides the username on small devices so only the image appears. -->
                  <span class="hidden-xs" id="nombre_usuario"><?php echo $nombre_usuario ?></span>
                </a>
                <ul class="dropdown-menu">
                  <!-- The user image in the menu -->
                  <li class="user-header">
                    <img src="<?php echo $foto ?>" class="img-circle" alt="User Image">
                    <p>
                      <?php echo $nombre_usuario ?>
                      <small>Miembro de Hergo LTDA</small>
                    </p>
                  </li>
                  <!-- Menu Body -->
                  <li class="user-body">
                  </li>
                  <li class="user-footer">
                    <div class="pull-left">
                      <a href="<?php echo base_url('index.php/auth/edit_user/'.$user_id_actual) ?>" class="btn btn-primary btn-flat">Perfil</a>
                    </div>
                    <div class="pull-right">
                      <a href="<?php echo base_url('index.php/auth/logout') ?>" class="btn btn-primary btn-flat">Cerrar Sesión</a>
                    </div>
                  </li>
                </ul>
              </li>
              <!-- Control Sidebar Toggle Button -->
              
            </ul>
          </div>
        </nav>
      </header>
