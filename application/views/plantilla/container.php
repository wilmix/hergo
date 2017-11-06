 <!-- Content Header (Page header) -->
    <section class="content-header">
      <h1>
        Escritorio
        <small>Principal</small>
      </h1>
      <ol class="breadcrumb">
        <li><a href="#"><i class="fa fa-dashboard"></i> Level</a></li>
        <li class="active">Here</li>
      </ol>
    </section>

    <!-- Main content -->
    <section class="content">

      <!-- Your Page Content Here -->
      <div class="row">
        <!-- **************ADMINISTRACION************** -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3>Artículos</h3>
              <p>Ver y crear Articulos</p>
            </div>
            <div class="icon">
              <i class="glyphicon glyphicon-th-list"></i>
            </div>
            <a href="<?php echo base_url("index.php/articulos") ?>" class="small-box-footer">
              Ir Articulos <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3>Clientes</h3>
              <p>Ver y añadir Cliente</p>
            </div>
            <div class="icon">
              <i class="fa fa-users"></i>
            </div>
            <a href="<?php echo base_url("index.php/clientes") ?>" class="small-box-footer">
              Ir Clientes <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3>Provedores</h3>
              <p>Ver y añadir Provedor</p>
            </div>
            <div class="icon">
              <i class="fa fa-user"></i>
            </div>
            <a href="<?php echo base_url("index.php/Provedores") ?>" class="small-box-footer">
              Ir Provedores <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-aqua">
            <div class="inner">
              <h3>Usuarios</h3>
              <p>Ver y añadir Usuarios</p>
            </div>
            <div class="icon">
              <i class="glyphicon glyphicon-user"></i>
            </div>
            <a href="<?php echo base_url('index.php/auth/usuarios') ?>" class="small-box-footer">
              Ir Usuarios <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- **************INGRESOS************* -->
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3>Ingresos<sup style="font-size: 20px"></sup></h3>
              <p>Consulta Ingresos</p>
            </div>
            <div class="icon">
              <i class="fa fa-plus-circle"></i>
            </div>
            <a href="<?php echo base_url("ingresos") ?>" class="small-box-footer">
              Ir a Consulta Ingresos <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3>Ingresos<sup style="font-size: 20px"></sup></h3>
              <p>Consulta Detalle</p>
            </div>
            <div class="icon">
              <i class="fa fa-search-plus"></i>
            </div>
            <a href="<?php echo base_url("ingresos/consultadetalle") ?>" class="small-box-footer">
              Ir a Consulta Detalle <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3>CL's</h3>
              <p>Compras Locales</p>
            </div>
            <div class="icon">
              <i class="glyphicon glyphicon-edit"></i>
            </div>
            <a href="<?php echo base_url("Ingresos/Compraslocales") ?>" class="small-box-footer">
              Ir a Compras Locales <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-green">
            <div class="inner">
              <h3>Importaciones<sup style="font-size: 20px"></sup></h3>
              <p>Ingreso por Importaciones</p>
            </div>
            <div class="icon">
              <i class="fa fa-download"></i>
            </div>
            <a href="<?php echo base_url("Ingresos/Importaciones") ?>" class="small-box-footer">
              Ir a Importaciones <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- **************EGRESOS************* -->
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3>Egresos</h3>
              <p>Consulta Egresos</p>
            </div>
            <div class="icon">
              <i class="fa fa-eject"></i>
            </div>
            <a href="<?php echo base_url("egresos") ?>" class="small-box-footer">
              Ir a Consulta <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3>Nota de Entrega</h3>
              <p>Crear NE</p>
            </div>
            <div class="icon">
              <i class="fa fa-sticky-note-o"></i>
            </div>
            <a href="<?php echo base_url("egresos/notaentrega") ?>" class="small-box-footer">
              Ir a Nota de Entrega <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3>Venta Caja</h3>
              <p>Crear VC</p>
            </div>
            <div class="icon">
              <i class="fa fa-shopping-cart"></i>
            </div>
            <a href="<?php echo base_url("egresos/VentasCaja") ?>" class="small-box-footer">
              Ir a Venta Caja <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-red">
            <div class="inner">
              <h3>Baja</h3>
              <p>Baja de Producto</p>
            </div>
            <div class="icon">
              <i class="fa fa-tag"></i>
            </div>
            <a href="<?php echo base_url("egresos/BajaProducto") ?>" class="small-box-footer">
              Ir a Baja de Producto <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- **************TRASPASOS************* -->
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3>Traspasos</h3>
              <p>Consultar Traspasos</p>
            </div>
            <div class="icon">
              <i class="fa fa-exchange"></i>
            </div>
            <a href="<?php echo base_url("Traspasos/index") ?>" class="small-box-footer">
              Ir a Consultar Traspasos <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-yellow">
            <div class="inner">
              <h3>Crear Traspaso</h3>
              <p>Crear Traspaso de Almacen</p>
            </div>
            <div class="icon">
              <i class="glyphicon glyphicon-resize-full"></i>
            </div>
            <a href="<?php echo base_url("Traspasos/traspasoEgreso") ?>" class="small-box-footer">
              Ir a Crear Traspaso <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
         <!-- **************FACTURACION************* -->
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-blue">
            <div class="inner">
              <h3>Facturación</h3>
              <p>Consultar Facturas</p>
            </div>
            <div class="icon">
              <i class="fa fa-list-ol"></i>
            </div>
            <a href="<?php echo base_url("facturas") ?>" class="small-box-footer">
              Ir a Consultar Facturas <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
          <!-- small box -->
          <div class="small-box bg-blue">
            <div class="inner">
              <h3>Factura QR</h3>
              <p>Crear Factura QR</p>
            </div>
            <div class="icon">
              <i class="fa fa-qrcode"></i>
            </div>
            <a href="<?php echo base_url("facturas/EmitirFactura") ?>" class="small-box-footer">
              Ir a Crear Factura  <i class="fa fa-arrow-circle-right"></i>
            </a>
          </div>
        </div>
        <!-- ./col -->
      </div>

    </section>
    <!-- /.content -->
  </div>
  <!-- /.content-wrapper -->