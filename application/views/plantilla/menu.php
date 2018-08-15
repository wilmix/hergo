<!-- ***MENU***Left side column. contains the logo and sidebar -->
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
        <p><?php echo $almacen_usuario ?></p>
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
      <ul class="sidebar-menu" id="masterMenu">
        <li class="header">MENU</li>

        <li class="treeview">
          <a href="#"><i class="fa fa fa-server"></i> <span>Administración</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">
            <li class="almacen"><a href="<?php echo base_url("index.php/almacen") ?>"><i class="fa fa-industry "></i> Almacen</a></li>
            <li class="articulos"><a href="<?php echo base_url("index.php/articulos") ?>"><i class="fa fa-list-ol "></i> Articulos</a></li>
            <li class="MarcaArticulos"><a href="<?php echo base_url("index.php/MarcaArticulos") ?>"><i class="fa fa fa-columns"></i> Marca Articulos</a></li>
            <li class="linea"><a href="<?php echo base_url("index.php/linea") ?>"><i class="fa fa fa-columns"></i> Linea</a></li>
            <li class="unidad"><a href="<?php echo base_url("index.php/unidad") ?>"><i class="fa fa fa-columns"></i> Unidad</a></li>
            <li class="clientes"><a href="<?php echo base_url("index.php/clientes") ?>"><i class="fa fa-users"></i> Clientes</a></li>
            <li class="Provedores"><a href="<?php echo base_url("index.php/Provedores") ?>"><i class="fa fa-users"></i> Provedores</a></li>
            <li class="CodigoControl"><a href="<?php echo base_url("index.php/CodigoControl") ?>"><i class="fa fa-qrcode"></i> CodigoControl QR</a></li>
            <li class="datosFactura"><a href="<?php echo base_url("index.php/configuracion/datosFactura") ?>"><i class="fa fa-cog"></i> Datos Factura</a></li>
            <li class="tipoCambio"><a href="<?php echo base_url("index.php/configuracion/tipoCambio") ?>"><i class="fas fa-dollar-sign"></i> Tipo de Cambio</a></li>
            <!--<li><a href="<?php echo base_url("index.php/usuarios") ?>"> FormFuncionarios</a></li>
            <!--<li><a href="#">Modificar Precis Articulos</a></li>
            <li><a href="#">Datos Factura</a></li>
            <li><a href="#">Roles</a></li>
            <li><a href="#">Opciones</a></li>-->

          </ul>
        </li>


        <li class="treeview">
          <a href="<?php echo base_url("ingresos") ?>"><i class="fa fa-plus-square"></i> <span>Ingresos</span>
            <span class="pull-right-container">
              <i class="fa fa-angle-left pull-right"></i>
            </span>
          </a>
          <ul class="treeview-menu">

            <li class="ingresos"><a href="<?php echo base_url("ingresos") ?>"><i class="fa fa-caret-square-o-down "></i>  Consultas</a></li>
            <!--<li><a href="<?php echo base_url("ingresos/consultadetalle") ?>"><i class="fa fa-cog fa-spin fa-fw"></i> ConsultasDetalle
              <small class="label pull-right bg-red"> construc</small></a></li>-->
              <li class="Compraslocales"><a href="<?php echo base_url("Ingresos/Compraslocales") ?>"><i class="fa fa-plus-circle"></i> Compras Locales</a></li>
              <li class="Importaciones"><a href="<?php echo base_url("Ingresos/Importaciones") ?>"><i class="fa fa-plus-circle"></i> Ingreso Importaciones</a></li>
              <li class="anulacionEgresos"><a href="<?php echo base_url("Ingresos/anulacionEgresos") ?>"><i class="fa fa-plus-circle"></i> Anulacion Egresos</a></li>
            </ul>
          </li>

          <li class="treeview">
            <a href="<?php echo base_url("egresos") ?>"><i class="fa fa-minus-square"></i> <span>Egresos</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li class="egresos"><a href="<?php echo base_url("egresos") ?>">Consultas</a></li>
              <li class="VentasCaja"><a href="<?php echo base_url("egresos/VentasCaja") ?>">Venta Caja</a></li>
              <li class="notaentrega"><a href="<?php echo base_url("egresos/notaentrega") ?>">Nota de Entrega</a></li>
              <li class="BajaProducto"><a href="<?php echo base_url("egresos/BajaProducto") ?>">Baja de Producto</a></li>
            </ul>
          </li>


          <li class="treeview">
            <a href="#"><i class="fa fa fa-exchange"></i> <span>Traspasos </span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li class="Traspasos"><a href="<?php echo base_url("Traspasos") ?>">Consultas</a></li>
              <li class="traspasoEgreso"><a href="<?php echo base_url("Traspasos/traspasoEgreso") ?>">FormTraspaso</a></li>
            </ul>
          </li>


          <li class="treeview">
            <a href="#"><i class="fa fa-money"></i> <span>Facturas</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li class="facturas"><a href="<?php echo base_url("facturas") ?>">Consulta de Facturas</a></li>
              <li class="EmitirFactura"><a href="<?php echo base_url("facturas/EmitirFactura") ?>">Emitir Factura</a></li>
            </ul>
          </li>

          <li class="treeview">
            <a href="#"><i class="fa fa-book"></i> <span>Pagos</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li class="Pagos"><a href="<?php echo base_url("Pagos") ?>">Consulta Pagos</a></li>
              <li class="RecibirPago"><a href="<?php echo base_url("Pagos/RecibirPago") ?>">Recibir Pagos</a></li>
              <!-- <li class=""><a href="#">Modificar Pagos</a></li> -->
            </ul>
          </li>


          <li class="treeview">
            <a href="#"><i class="fa fa fa-line-chart"></i> <span>Reportes</span>
              <span class="pull-right-container">
                <i class="fa fa-angle-left pull-right"></i>
              </span>
            </a>
            <ul class="treeview-menu">
              <li class="listaPrecios"><a href="<?php echo base_url("reportes/listaPrecios") ?>">Lista de Precios</a></li>
              <li class="saldosActuales"><a href="<?php echo base_url("reportes/saldosActuales") ?>">Saldos Actuales</a></li>
              <li class="facturasPendietesPago"><a href="<?php echo base_url("reportes/facturasPendietesPago") ?>">Facturas Pendientes Pago</a></li>
              <li class="resumenVentasLineaMes"><a href="<?php echo base_url("reportes/resumenVentasLineaMes") ?>">Resumen de Ventas Linea Mes</a></li>
              <li class="notasEntregaPorFacturar"><a href="<?php echo base_url("reportes/notasEntregaPorFacturar") ?>">Notas de Entrega por Facturar</a></li>
              <li class="facturacionClientes"><a href="<?php echo base_url("reportes/facturacionClientes") ?>">Facturacion Clientes</a></li>
              <li class="diarioIngresos"><a href="<?php echo base_url("reportes/diarioIngresos") ?>">Diario de Ingreso</a></li>
              <li class="libroVentas"><a href="<?php echo base_url("reportes/libroVentas") ?>">Libro de Ventas</a></li>
              <li class="kardexIndividualValorado"><a href="<?php echo base_url("reportes/kardexIndividualValorado") ?>">Kardex Individual Valorado</a></li>
              <li><a href="<?php echo base_url("reportes/kardexIndividualCliente") ?>">Kardex Individual Clientes</a></li>
            <!--<li><a href="<?php echo base_url("reportes/movimientosClientes") ?>">Movimientos Item Clientes</a></li>
            <li><a href="<?php echo base_url("reportes/resumenVentaCliente") ?>">Resumen de Ventas por             <li><a href="<?php echo base_url("reportes/diarioPagos") ?>">Diario de Pagos</a></li>
            <li><a href="<?php echo base_url("reportes/resumenProductosUnidades") ?>">Resumen de Productos en Unidades</a></li>
            <li><a href="<?php echo base_url("reportes/estadoVentasCostoItem") ?>">Estado de Ventas y Costos</a></li>
            <li><a href="<?php echo base_url("reportes/kardexIndividual") ?>">Kardex Individual Itemes</a></li>
            
          -->
        </ul>
      </li>
      <li class="treeview">
        <a href="#"><i class="fa fa-users"></i> <span>Usuarios</span>
          <span class="pull-right-container">
            <i class="fa fa-angle-left pull-right"></i>
          </span>
        </a>
        <ul class="treeview-menu">
          <li class="usuarios"><a href="<?php echo base_url('index.php/auth/usuarios') ?>">Ver Usuarios</a></li>
          <li class="create_user"><a href="<?php echo base_url('index.php/auth/create_user') ?>">Nuevo Usuario</a></li>
          <li class="create_group"><a href="<?php echo base_url('index.php/auth/create_group') ?>">Nuevo Grupo</a></li>

        </ul>
      </li>


    </ul>
    <!-- /.sidebar-menu -->
  </section>
  <!-- /.sidebar -->
</aside>

<!-- Content Wrapper. Contains page content -->
<div class="content-wrapper">