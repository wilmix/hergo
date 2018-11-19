
<style>
  body {
    /* el tamaño por defecto es 14px */
    font-size: 16px;
}
</style>
<body class="skin-blue sidebar-mini fixed">
<input type="hidden" id="baseurl" value="<?php echo base_url() ?>">
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
    <nav class="navbar navbar-static-top" role="navigation">
      <!-- Sidebar toggle button-->
      <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
        <span class="sr-only">Toggle navigation</span>
      </a>
      <!-- Navbar Right Menu -->
      <div class="navbar-custom-menu">
        <ul class="nav navbar-nav">
          <!-- Tipo de cambio -->
          <li>
            <a href="<?php echo base_url("index.php/configuracion/tipoCambio") ?>">
              <i class="fas fa-dollar-sign"> T/C:  <span id="mostrarTipoCambio" ><?= $tipoCambio ?></span> </i>
            </a>
          </li>
          <!-- User Account Menu -->
          <li class="dropdown user user-menu">
            <!-- Menu Toggle Button -->
            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
              <!-- The user image in the navbar -->
              <img src="<?php echo $foto ?>" class="user-image" alt="User Image">
              <!-- hidden-xs hides the username on small devices so only the image appears. -->
              <span class="hidden-xs"><?php echo $nombre_usuario ?></span>
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
                <div class="text-center">
                  <a href="<?php echo base_url('index.php/auth/logout') ?>" class="btn btn-default btn-flat">Cerrar sesion</a>
                </div>
              </li>
            </ul>
          </li>
          <!-- Control Sidebar Toggle Button -->
          
        </ul>
      </div>
    </nav>
  </header>
