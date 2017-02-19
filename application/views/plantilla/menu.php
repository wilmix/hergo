<!-- Left side column. contains the logo and sidebar -->
  <aside class="main-sidebar">

    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

      <!-- Sidebar user panel (optional) -->
      <div class="user-panel">
        <div class="pull-left image">
          <img src="<?php echo $foto?>" class="img-circle" alt="User Image">
        </div>
        <div class="pull-left info">
          <p><?php echo $nombre_usuario ?></p>
          <!-- Status -->
          <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
        </div>
      </div>

      <!-- search form (Optional) -->
      <!--<form action="#" method="get" class="sidebar-form">
        <div class="input-group">
          <input type="text" name="q" class="form-control" placeholder="Search...">
              <span class="input-group-btn">
                <button type="submit" name="search" id="search-btn" class="btn btn-flat"><i class="fa fa-search"></i>
                </button>
              </span>
        </div>
      </form> -->
      <!-- /.search form -->

      <!-- Sidebar Menu -->
      <ul class="sidebar-menu">
        <li class="header">MENU</li>
       
               <li class="treeview">
          <a href="#"><i class="fa fa fa-server"></i> <span>Administración</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="<?php echo base_url("index.php/almacen") ?>">Almacen</a></li>
            <li><a href="<?php echo base_url("index.php/articulos") ?>">Articulos</a></li>
            <li><a href="<?php echo base_url("index.php/marcaarticulos") ?>">Marca Articulos</a></li>
            <li><a href="<?php echo base_url("index.php/linea") ?>">Linea</a></li>
            <li><a href="<?php echo base_url("index.php/unidad") ?>">Unidad</a></li>
            <li><a href="<?php echo base_url("index.php/clientes") ?>">Clientes</a></li>
            <li><a href="<?php echo base_url("index.php/Provedores") ?>">Provedores</a></li>
            <li><a href="<?php echo base_url("index.php/usuarios") ?>"> FormFuncionarios</a></li>
            <!--<li><a href="#">Modificar Precis Articulos</a></li>
            <li><a href="#">Datos Factura</a></li>
            <li><a href="#">Roles</a></li>
            <li><a href="#">Opciones</a></li>-->
          </ul>
        </li>


          <li class="treeview">
          <a href="#"><i class="fa fa-plus-square"></i> <span>Ingresos</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="<?php echo base_url("ingresos") ?>">Consultas</a></li>
            <li><a href="<?php echo base_url("Ingresos/Importaciones") ?>">Ingreso Importaciones</a></li>
            <li><a href="#">Traspasos</a></li>
            <li><a href="#">Devolución Cliente</a></li>
            <li><a href="#">Anulacion de Egreso</a></li>
          </ul>
        </li>
              
          <li class="treeview">
          <a href="#"><i class="fa fa-minus-square"></i> <span>Egresos</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="#">Consultas</a></li>
            <li><a href="#">Venta Caja</a></li>
            <li><a href="#">Nota de Entrega</a></li>
            <li><a href="#">Traspasos</a></li>
            <li><a href="#">Baja de Producto</a></li>
          </ul>
        </li>



          <li class="treeview">
          <a href="#"><i class="fa fa-money"></i> <span>Facturas</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="#">Emitir Factura</a></li>
            <li><a href="#">Ver Facturas</a></li>
            <li><a href="#">Consulta de Facturas</a></li>
          </ul>
        </li>


        <li class="treeview">
          <a href="#"><i class="fa fa-book"></i> <span>Pagos</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="#">Consulta Pagos</a></li>
            <li><a href="#">Recibir Pagos</a></li>
            <li><a href="#">Modificar Pagos</a></li>
          </ul>
        </li>


        <li class="treeview">
          <a href="#"><i class="fa fa fa-line-chart"></i> <span>Reportes</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="#">Lista de Precios</a></li>
            <li><a href="#">Saldos Actuales</a></li>
            <li><a href="#">Estado de Ventas y Costos</a></li>
            <li><a href="#">Modificar Precios Articulos</a></li>
            <li><a href="#">Kardex Individual Valorado</a></li>
            <li><a href="#">Kardex Individual Itemes</a></li>
            <li><a href="#">Resumen de Ventas Mes</a></li>
            <li><a href="#">Facturas Pendientes Pago</a></li>
            <li><a href="#">Notas de Entrega por Facturar</a></li>
            <li><a href="#">Facturacion Clientes</a></li>
            <li><a href="#">Nota de Ingreso</a></li>
            <li><a href="#">Libro de Ventas</a></li>
          </ul>
        </li>
        <li class="treeview">
          <a href="#"><i class="fa fa-users"></i> <span>Usuarios</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li><a href="<?php echo base_url('index.php/auth/usuarios') ?>">Ver Usuarios</a></li>
            <li><a href="<?php echo base_url('index.php/auth/create_user') ?>">Nuevo Usuario</a></li>
            <li><a href="<?php echo base_url('index.php/auth/create_group') ?>">Nuevo Grupo</a></li>
            
          </ul>
        </li>


      </ul>
      <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
  </aside>

  <!-- Content Wrapper. Contains page content -->
  <div class="content-wrapper">